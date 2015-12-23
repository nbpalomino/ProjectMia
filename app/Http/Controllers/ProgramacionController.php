<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Taller;
use App\Programacion;
use App\Grupo;
use DB;
use Auth;

use DateTime;
use DateInterval;
use Illuminate\Http\Request;

class ProgramacionController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $progs = $this->programaciones();

        if ($request->has('zona')) {
            $progs->where('zona_id', $request->get('zona'));
        }
        if ($request->has('fecha_inicio')) {
            $progs->where('fecha_inicio', '>=', $request->get('fecha_inicio'));
        }
        if ($request->has('fecha_fin')) {
            $progs->where('fecha_fin', '<=', $request->get('fecha_fin'));
        }

        $programaciones = $progs->get();

        $programaciones->each(function($programacion){

            $programacion->f_inicio = $programacion->fecha($programacion->fecha_inicio)->format('d/m/Y');
            $programacion->f_fin    = $programacion->fecha($programacion->fecha_fin)->format('d/m/Y');
        });

        return $programaciones;
    }

    protected function programaciones($grupo_id = null)
    {
        $programaciones = Programacion::activo();

        if ( Auth::user()->esEjecutivo() && Auth::user()->grupo ) {
            $grupo = Auth::user()->grupo()->with('zonas')->first();
        }

        if ( $grupo_id ) {
            $grupo = Grupo::with('zonas')->find($grupo_id);
        }

        if ($grupo_id || (Auth::user()->esEjecutivo() && Auth::user()->grupo) ) {
            $zonas = $grupo->zonas->map(function($zona){

                return $zona->id;
            });

            $programaciones->whereIn('zona_id', $zonas->toArray());
        }

        return $programaciones;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $this->mensaje['tipo']  = 'success';
        $this->mensaje['texto'] = "Ocurrió un error al registrar los datos";

        try {

            // $this->existeFecha($data['fecha_inicio'], $data['fecha_fin'], $data['zona_id']);

            Programacion::create($data);
            $this->mensaje['texto'] = "Se registro correctamente";
        } catch (\Exception $e) {
            $this->mensaje['tipo']  = 'warning';
            // $this->mensaje['texto']  .= " [{$e->getMessage()}]";
        }

        return ['mensaje'=>$this->mensaje, 'success'=>true];
    }

    /**
     * Registrar un nuevo Taller y asignarle la Visita correcta
     *
     * @return Response
     */
    public function taller(Request $request)
    {
        $data = $request->all();

        $dataTaller = $request->only(['nombre', 'ruc', 'contacto', 'email', 'telefono', 'celular', 'estado', 'distrito_id', 'zona_id', 'direccion']);

        $this->mensaje['tipo']  = 'success';
        $this->mensaje['texto'] = "Ocurrió un error al registrar los datos";

        $programacion   = $this->programacionZonaFecha($data['zona_id'], $data['fecha_visita'])->first();
        try {
            // Actualizamos el orden de las Visitas
            $orden = $this->ordenVisitas($programacion, $data['fecha_visita'], $data['orden']);

            // Registramos el nuevo Taller
            $taller = Taller::create($dataTaller);

            // Registramos el nuevo Taller en la Visita correcta
            $programacion->visitas()->attach($taller, ['fecha_visita'=>$data['fecha_visita'], 'orden'=>$orden]);


            $this->mensaje['texto'] = "Se registro correctamente";
        } catch (\Exception $e) {
            $this->mensaje['tipo']  = 'warning';
            $this->mensaje['texto']  .= " [{$e->getMessage()}]";
        }

        return ['mensaje'=>$this->mensaje, 'success'=>true];
    }

    protected function ordenVisitas($programacion, $fechaVisita, $newOrden)
    {
        $queryVisitas   = $programacion->visitas()->where('fecha_visita', $fechaVisita);

        // Obtenemos el Orden maximo
        $maxOrden = $queryVisitas->max('orden');
        // $maxOrden = $visitas->reduce(function($orden, $taller)
        // {
        //     return $orden = $orden < $taller->pivot->orden ? $taller->pivot->orden : $orden;
        // });

        if ( $newOrden > $maxOrden ) {
            return $maxOrden + 1;
        } else {

            $visitas    = $queryVisitas->get();

            foreach ($visitas as $taller)
            {
                $orden = $taller->pivot->orden;

                if ($orden >= $newOrden) {

                    $programacion->visitas()->updateExistingPivot($taller->id, ['orden'=>$orden + 1]);
                }
            };

            return $newOrden;
        }

    }

    protected function programacionZonaFecha($zona, $fecha)
    {
        return Programacion::whereRaw("activo = 1 AND zona_id = {$zona} AND '{$fecha}' BETWEEN fecha_inicio AND fecha_fin");
    }

    private function existeFecha($inicio, $fin, $zona_id)
    {
        $prog_inicio    = $this->programacionZonaFecha($zona_id, $inicio)->get();
        $prog_fin       = $this->programacionZonaFecha($zona_id, $fin)->get();
        $this->mensaje['texto'] = '';

        if ( !$prog_inicio->isEmpty() ){
            $this->mensaje['texto']     .= "La fecha de inicio no se puede registrar debido a que existe dentro del rango de otras fechas.<br><br>";

            // foreach ($prog_inicio as $p) {
            //     $this->mensaje['texto']    .= "> ".$p->fecha($p->fecha_inicio)->format('d/m/Y')." - ".$p->fecha($p->fecha_fin)->format('d/m/Y')."<br>";
            // }
        }

        if ( !$prog_fin->isEmpty() ){
            $this->mensaje['texto']     .= "La fecha de fin no se puede registrar debido a que existe dentro del rango de otras fechas.<br><br>";

            // foreach ($prog_fin as $p) {
            //     $this->mensaje['texto']    .= "> ".$p->fecha($p->fecha_inicio)->format('d/m/Y')." - ".$p->fecha($p->fecha_fin)->format('d/m/Y')."<br>";
            // }
        }

        if ( !$prog_inicio->isEmpty() || !$prog_fin->isEmpty() )
        {
            throw new \Exception("Error de fechas dentro del rango de otras fechas", 1);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Programacion::activo()->find($id);
    }

    /**
     * Muestra y Guarda los Talleres disponibles para una Visita
     *
     * @param  int  $id
     * @param  Date $fecha
     * @return Response
     */
    public function visitas($id, $fecha, Request $request)
    {
        $programacion   = Programacion::activo()->with('visitas')->find($id);
        $visitas        = $programacion->visitas()->where('fecha_visita', $fecha)->get();


        if ( $request->isMethod('get') )
        {
            $taller       = Taller::where('activo', 1);

            if ($request->has('distrito')) {
                $taller = $taller->where('distrito_id', $request->get('distrito'));
            }
            if ($request->has('zona')) {
                $taller = $taller->where('zona_id', $request->get('zona'));
            }
            // if ($request->has('estado')) {
            //     $taller = $taller->where('estado', "{$request->get('estado')}");
            // }

            $talleres = $taller->orderBy('nombre', 'ASC')->get();

            $data['all']        = [];
            $data['selected']   = [];

            // Todos los Talleres en la Fecha elegida visitados
            foreach ($visitas as $taller)
            {
                $taller_encontrado = $talleres->find($taller);

                // Solo registramos Talleres que cumplan la condicion
                if ( $taller_encontrado )
                {
                    $taller_encontrado->selected = true;
                }

                $data['selected'][] = ['name'=>$taller->nombre." [{$taller->distrito->nombre}]", 'value'=>$taller->id, 'selected'=>true ];
            }

            // Todos los Talleres en general visitados
            foreach ($programacion->visitas as $taller)
            {
                $taller = $talleres->find($taller);

                // Solo registramos Talleres que cumplan la condicion
                if ( $taller )
                {
                    $taller->selected = true;
                }
            }

            // Todos los Talleres disponibles
            foreach ($talleres as $taller)
            {
                $data['all'][] = ['name'=>$taller->nombre." [{$taller->distrito->nombre}]", 'value'=>$taller->id, 'selected'=>$taller->selected ? true : false];
            }

            return $data;

        }
        else if ( $request->isMethod('post') ) {
            $data = $request->all();

            $mensaje['tipo']  = 'success';
            $mensaje['texto'] = "Se registro correctamente";

            if ( empty($data['tallerElegidos']) ) {
                $mensaje['tipo']  = 'warning';
                $mensaje['texto'] = "No existen datos que registrar";

                return ['mensaje'=>$mensaje, 'success'=>true];
            }

            foreach ($data['tallerElegidos'] as $orden => $taller)
            {
                $taller = Taller::find($taller['value']);

                // Si el Taller no existe previamente lo registramos
                if ( !$visitas->find($taller) ) {
                    $programacion->visitas()->attach($taller, ['fecha_visita'=>$data['fecha_visita'], 'orden'=>$orden + 1]);
                }

            }

            return ['mensaje'=>$mensaje, 'success'=>true];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateVisita($id, $taller_id, $fecha, Request $request)
    {
        $success = true;
        $data    = $request->all();

        $mensaje['tipo']     = 'success';
        $mensaje['texto']    = "Se actualizó correctamente";

        try {
            $taller = Taller::find($taller_id);
            $taller->rectificado    = $data['rectificado'];
            $taller->arregla_motos  = $data['arregla_motos'];
            $taller->save();

            // $programacion   = DB::table('programacion_taller')
            //                     ->whereRaw('programacion_id = ? AND taller_id = ? AND fecha_visita = ?', [$id, $taller, $fecha])
            //                     ->update();

            $programacion = Programacion::with('visitas')->find($id);

            $programacion->visitas()->updateExistingPivot($taller_id, ['visitado'=>$data['visitado'], 'motivo_no_visita'=>$data['motivo']]);


        } catch (\Exception $e) {
            $success = false;

            $mensaje['tipo']     = 'warning';
            $mensaje['texto']    = "Ocurrió un error al actualizar. [{$e->getMessage()}]";
        }

        return ['mensaje'=>$mensaje, 'foo'=>$data, 'success'=>$success];
    }

    /**
     * Muestra todas las Visitas según la Fecha
     *
     * @param  int  $id
     * @return Response
     */
    public function fechaVisita($fecha, Request $request)
    {
        $data       = $request->all();
        $visitas    = DB::table('programacion_taller')->where('fecha_visita', $fecha);

        $programaciones = $this->programaciones($request->get('grupo'))->get()->map(function($programacion){
                return $programacion->id;
            });

        $visitas->whereIn('programacion_id', $programaciones->toArray());

        if ( $request->has('zona') )
        {
            $talleres = Taller::activo()->where('zona_id', $request->get('zona'))->get();
        }
        if ( $request->has('distrito') )
        {
            $talleres = Taller::activo()->where('distrito_id', $request->get('distrito'))->get();
        }

        if ( $request->has('zona') || $request->has('distrito')) {
            $talleres = $talleres->map(function($taller){
                    return $taller->id;
                });

            $visitas->whereIn('taller_id', $talleres->toArray());
        }

        $visitas = $visitas->get();

        $data = [];
        $models = [];
        foreach ($visitas as $key => $visita) {
            $programacion   = $visita->programacion_id;
            $taller         = $visita->taller_id;

            $models['programacion'.$programacion]   = isset($models['programacion'.$programacion]) ? $models['programacion'.$programacion] : Programacion::activo()->find($programacion);
            $models['taller'.$taller]               = isset($models['taller'.$taller]) ? $models['taller'.$taller] : Taller::activo()->find($taller);


            $data[$key]['programacion'] = $models['programacion'.$programacion];
            $data[$key]['taller']       = $models['taller'.$taller];
            $data[$key]['fecha_visita'] = $visita->fecha_visita;
            $data[$key]['motivo_no_visita'] = $visita->motivo_no_visita;
            $data[$key]['visitado']     = $visita->visitado;
            $data[$key]['orden']        = $visita->orden;
        }

        return ['data'=>$data, 'success'=>true];
    }

    /**
     * Duplicar la Programacion elegida
     *
     * @param  int  $id
     * @return Response
     */
    public function duplicate($id, Request $request)
    {
        $data = $request->all();
        $visitas = [];

        $this->mensaje['tipo']  = 'success';
        $this->mensaje['texto'] = "Ocurrió un error al registrar los datos";

        $programacion = Programacion::activo()->with('visitas')->find($id);

        try {

            // $this->existeFecha($data['fecha_inicio'], $data['fecha_fin'], $data['zona_id']);

            $data['fecha_fin'] = $this->comprobarFechaDomingo( $data['fecha_fin'] );

            $nuevaProgramacion = Programacion::create($data);

            foreach ($programacion->visitas as $taller) {
                $fechaInicio    = $programacion->fecha($programacion->fecha_inicio);
                $fechaVisita    = $programacion->fecha($taller->pivot->fecha_visita);

                // Obtenemos los dias a sumar a la nueva Fecha de Visita
                $diasExtra      = $fechaInicio->diff( $fechaVisita );

                $nuevaFechaInicio   = $programacion->fecha($nuevaProgramacion->fecha_inicio);

                $nuevaFechaVisita   = $this->comprobarFechaDomingo( $nuevaFechaInicio->add( $diasExtra ) );

                $visitas[$taller->id] = ['fecha_visita'=>$nuevaFechaVisita, 'orden'=>$taller->pivot->orden];
            }

            if ( !empty($visitas) )
            {
                $nuevaProgramacion->visitas()->sync($visitas);
            }

            $this->mensaje['texto'] = "Se registro correctamente";
        } catch (\Exception $e) {
            $this->mensaje['tipo']  = 'warning';
            $this->mensaje['texto']  .= " [{$e->getMessage()}]";
        }

        return ['mensaje'=>$this->mensaje, 'success'=>true];
    }

    /**
     * Si la fecha es Domingo lo pasamos a Lunes
     *
     * @param  mixed $fecha
     * @return DateTime
     */
    private function comprobarFechaDomingo($fecha)
    {
        if ( !$fecha instanceof DateTime ) {
            $fecha = new DateTime($fecha);
        }

        // Si el dia es Domingo le sumamos 1 dia.
        if ( date('l', $fecha->getTimestamp()) === 'Sunday' ){
            $fecha->add( new DateInterval('P1D') );
        }

        return $fecha;
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
            $programacion = Programacion::find($id);
            $programacion->nombre = $request->get('nombre');
            $programacion->save();

            $mensaje['texto'] = "Se actualizó correctamente la Programacion.";
        } catch (\Exception $e) {
            $mensaje = ['tipo'=>'warning', 'texto'=>"Ocurrió un error al actualizar la Programacion"];
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
            $programacion = Programacion::find($id);
            $programacion->activo = 0;
            $programacion->save();

            $mensaje['texto'] = "Se eliminó correctamente la Programacion.";
        } catch (\Exception $e) {
            $mensaje = ['tipo'=>'warning', 'texto'=>"Ocurrió un error al eliminar la Programacion"];
        }

        return ['mensaje'=>$mensaje, 'success'=>true];
    }

}
