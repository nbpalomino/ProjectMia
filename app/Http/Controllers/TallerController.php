<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Taller;
use App\Empaque;
use App\Premio;
use App\Grupo;
use Auth;

use Illuminate\Http\Request;

class TallerController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $talleres = $this->talleres($request->get('grupo'));
        if ($request->has('distrito')) {
            $talleres->where('distrito_id', $request->get('distrito'));
        }
        if ($request->has('zona')) {
            $talleres->where('zona_id', $request->get('zona'));
        }
        if ($request->has('texto')) {
            $texto = $request->get('texto');
            $talleres->whereRaw("(ruc LIKE '%{$texto}%' OR nombre LIKE '%{$texto}%' OR contacto LIKE '%{$texto}%')");
        }

        return ['data'=>$talleres->get(), 'success'=>true];
    }

    protected function talleres($grupo_id = null)
    {
        $talleres = Taller::activo();

        if ( Auth::user()->esEjecutivo() && Auth::user()->grupo  ) {
            $grupo = Auth::user()->grupo()->with('zonas')->first();
        }
        if ( $grupo_id ) {
            $grupo = Grupo::with('zonas')->find($grupo_id);
        }

        if ($grupo_id || (Auth::user()->esEjecutivo() && Auth::user()->grupo) ) {
            $zonas = $grupo->zonas->map(function($zona){

                return $zona->id;
            });

            $talleres->whereIn('zona_id', $zonas->toArray());
        }

        return $talleres;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $mensaje['tipo']    = 'success';
        $mensaje['texto']   = "El registro se guardo correctamente";

        try {
            Taller::create($request->all());
        } catch (\Exception $e) {
            $mensaje['tipo']    = 'warning';
            $mensaje['texto']   = "Ocurrió un problema al registrar.";
        }

        return ['mensaje'=>$mensaje, 'success'=>true];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Taller::find($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function empaques($id)
    {
        $taller     = Taller::with('empaques')->find($id);
        $empaques   = Empaque::where('activo', 1)->get();

        //$taller->empaques()->where('empaque_taller.fecha_visita', '2015-05-19')->get();

        // Se saca todos los totales de puntos y empaques del Taller
        $totales = $taller->empaques->reduce(function($total, $empaque){
            if( !isset($total['puntos']) ) {
                $total['puntos']    = 0;
                $total['empaques']  = 0;
            }

            $total['puntos']    += $empaque->pivot->total_puntos;
            $total['empaques']  += $empaque->pivot->total_empaques;

            return $total;
        });

        // Si el Empaque existe en el listado de Empaques se le crea un atributo cantidad
        foreach ($taller->empaques as $emp) {
            $empaque = $empaques->find( $emp );

            if ($empaque) {
                $empaque->cantidad = $emp->pivot->total_empaques;
            }
        }

        $data['empaques']   = $empaques;
        $data['taller']     = $taller;
        $data['total_puntos']     = $totales['puntos'];
        $data['total_empaques']   = $totales['empaques'];

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function premios($id)
    {
        $taller    = Taller::with('premios')->find($id);
        $premios   = Premio::where('activo', 1)->get();

        // Se saca todos los totales de premios del Taller
        $totales = $taller->premios->reduce(function($total, $premio){
            if( !isset($total['premios']) ) {
                $total['premios']  = 0;
            }

            $total['premios']  += $premio->pivot->total_premios;

            return $total;
        });

        // Si el Premio existe en el listado de Premios se le crea un atributo cantidad
        foreach ($taller->premios as $prem) {
            $premio = $premios->find( $prem );

            if ($premio) {
                $premio->cantidad = $prem->pivot->total_premios;
            }
        }

        $data['premios']    = $premios;
        $data['taller']     = $taller;
        $data['total_premios']   = $totales['premios'];

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function savePremios($id, Request $request)
    {
        $taller     = Taller::with('premios', 'empaques')->find($id);
        $data       = $request->all();

        $total_puntos_canjeados = 0;
        $total_puntos           = 0;
        $saveData               = [];

        if ( !empty($data['cantidad']) )
        {
            // Obtenemos el Total de Puntos del Taller
            $total_puntos = $taller->empaques->reduce(function($total, $empaque){
                return $total += $empaque->pivot->total_puntos;
            });

            foreach ($data['cantidad'] as $premio => $cantidad)
            {
                $premio = Premio::find($premio);

                $total_puntos_canjeados    += $cantidad * $premio->valor;

                // Solo registramos Premios que cumplan la condicion
                if ( $cantidad > 0)
                {
                    $saveData[] = ['premio'=>$premio, 'pivot'=>['total_premios'=>$cantidad]];
                }
            }

            $taller->puntos_disponible = $total_puntos - $total_puntos_canjeados;
            if ( $taller->puntos_disponible < 0 )
            {
                return ['mensaje'=>['tipo'=>'warning', 'texto'=>"No tiene puntos suficientes para canjear"], 'success'=>true];
            }
            $taller->save();


            $taller->premios()->detach();

            foreach ($saveData as $datos)
            {
                $taller->premios()->attach($datos['premio'], $datos['pivot']);
            }

            return ['mensaje'=>['tipo'=>'success', 'texto'=>"Se registro correctamente"], 'success'=>true];
        }


        return ['mensaje'=>['tipo'=>'warning', 'texto'=>"No existen datos que registrar"], 'success'=>true];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function saveEmpaques($id, Request $request)
    {
        $taller     = Taller::with('empaques')->find($id);
        $data       = $request->all();

        if ( !empty($data['cantidad']) )
        {
            $taller->empaques()->detach();

            foreach ($data['cantidad'] as $empaque => $cantidad)
            {
                if ( $cantidad > 0) {
                    $empaque = Empaque::find($empaque);

                    $pivotData['total_empaques']    = $cantidad;
                    $pivotData['total_puntos']      = $cantidad * $empaque->valor;

                    $taller->empaques()->attach($empaque, $pivotData);
                }

            }

            $taller->save();

            return ['mensaje'=>['tipo'=>'success', 'texto'=>"Se registro correctamente"], 'success'=>true];
        }

        return ['mensaje'=>['tipo'=>'warning', 'texto'=>"No existen datos que registrar"], 'success'=>true];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $mensaje = ['tipo'=>'success'];
        try {
            $taller = Taller::find($id);
            $taller->fill($request->all());
            $taller->save();

            $mensaje['texto'] = "Se actualizó correctamente el Taller {$taller->nombre}.";
        } catch (\Exception $e) {
            $mensaje = ['tipo'=>'warning', 'texto'=>"Ocurrió un error al actualizar el Taller"];
        }

        return ['mensaje'=>$mensaje, 'success'=>true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        $mensaje = ['tipo'=>'success'];
        try {
            $taller = Taller::find($id);
            $taller->activo = 0;
            $taller->motivo_eliminado = $request->get('motivo_eliminado', 'No ingresó motivo');
            $taller->save();

            $mensaje['texto'] = "Se eliminó el Taller {$taller->nombre} correctamente";
        } catch (\Exception $e) {
            $mensaje = ['tipo'=>'warning', 'texto'=>"Ocurrió un error al eliminar el Taller."];
        }

        return ['mensaje'=>$mensaje, 'success'=>true];
    }

}
