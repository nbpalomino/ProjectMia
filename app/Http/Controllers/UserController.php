<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Taller;
use App\Premio;
use App\Empaque;
use App\Grupo;
use App\Programacion;
use Auth;
use DB;

use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Http\Request;

class UserController extends Controller {

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
     * @return void
     */
    public function __construct(Registrar $registrar)
    {
        $this->registrar = $registrar;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return User::where('activo', '=', 1)->get();
    }

    /**
     * Show dashboard stats
     *
     * @param  int  $id
     * @return Response
     */
    public function dashboard(Request $request)
    {
        // Comprobamos si la peticion es para llenar la tabla
        if ($request->has('table')) {
            return $this->mostrarTabla($request);
        }


        $stats['fotoSubidas']       = 123;
        $stats['canjeRealizados']   = $this->empaqueTaller()->sum('total_empaques');
        $stats['tallerNuevos']      = $this->taller()->where('estado', 'Nuevo')->count();
        $stats['tallerEliminados']  = $this->taller()->where('estado', 'Descartado')->count();
        $stats['tallerVisitados']   = $this->programacionTaller()->where('pt.visitado', 1)->count();
        $stats['tallerRectificadores']  = $this->taller()->where('rectificado', 1)->count();
        $stats['tallerArreglaMotos']    = $this->taller()->where('arregla_motos', 1)->count();
        //$stats['tallerPendientes']  = $tallerPendientes->where('pt.visitado', null)->count();


        $tables['talleres']         = $this->empaqueTaller()->select(
                                            DB::raw('SUM(et.total_empaques) AS canjes'),
                                            't.id AS id',
                                            't.nombre AS taller',
                                            't.contacto AS contacto',
                                            't.ruc AS ruc',
                                            'z.nombre AS zona',
                                            'd.nombre AS distrito'
                                        )
                                        ->orderBy('et.total_empaques', 'DESC')->groupBy('et.taller_id')->take(10)->get();
        $tables['premios']          = $this->premioTaller()->join('premios AS p', 'pt.premio_id', '=', 'p.id')->select(
                                            DB::raw('SUM(pt.total_premios) AS canjes'),
                                            'p.id AS id',
                                            'p.nombre AS premio',
                                            'p.valor AS valor'
                                        )
                                        ->orderBy('pt.total_premios', 'DESC')->groupBy('pt.premio_id')->take(10)->get();
        $tables['empaques']         = $this->empaqueTaller()->join('empaques AS e', 'et.empaque_id', '=', 'e.id')->select(
                                            DB::raw('SUM(et.total_empaques) AS canjes'),
                                            'e.id AS id',
                                            'e.nombre AS empaque',
                                            'e.marca AS marca'
                                        )
                                        ->groupBy('et.empaque_id')->take(10)->orderBy('et.total_empaques', 'DESC')->get();


        return ['stats'=>$stats, 'talleres'=>$tables['talleres'], 'premios'=>$tables['premios'], 'empaques'=>$tables['empaques'], 'success'=>true];
    }

    protected function mostrarTabla(Request $request)
    {
        $result = ['tipo'=>'danger', 'titulo'=>'Talleres con consulta'];
        $table  = $request->get('table', 'canjes');
        $field_zona     = 'zona_id';
        $field_distrito = 'distrito_id';

        if ($table == 'canjes') {
            $result['tipo']     = 'primary';
            $result['titulo']   = 'Talleres con canjes realizados';

            $field_zona     = 't.zona_id';
            $field_distrito = 't.distrito_id';

            $result['data']     = $this->empaqueTaller($request->get('grupo'))->select(
                                        DB::raw('SUM(et.total_empaques) AS canjes'),
                                        't.id AS id',
                                        't.nombre AS nombre',
                                        't.contacto AS contacto',
                                        't.ruc AS ruc',
                                        'z.nombre AS zona.nombre',
                                        'd.nombre AS distrito.nombre'
                                    )
                                    ->orderBy('t.nombre', 'ASC')->groupBy('et.taller_id');
        }
        if ($table == 'visitados') {
            $result['tipo']     = 'info';
            $result['titulo']   = 'Talleres visitados';

            $field_zona     = 't.zona_id';
            $field_distrito = 't.distrito_id';

            $result['data']     = $this->programacionTaller($request->get('grupo'))->select(
                                        't.id AS id',
                                        't.nombre AS nombre',
                                        't.contacto AS contacto',
                                        't.ruc AS ruc',
                                        'z.nombre AS zona.nombre',
                                        'd.nombre AS distrito.nombre'
                                    )
                                    ->where('pt.visitado', 1)->orderBy('t.nombre', 'ASC')->groupBy('t.id');
        }
        if ($table == 'nuevos') {
            $result['tipo']     = 'success';
            $result['titulo']   = 'Talleres nuevos';

            $result['data']     = $this->taller($request->get('grupo'))->where('estado', 'Nuevo')
                                    ->orderBy('nombre', 'ASC');
        }
        if ($table == 'eliminados') {
            $result['tipo']     = 'default';
            $result['titulo']   = 'Talleres eliminados';

            $result['data']     = $this->taller($request->get('grupo'))->where('estado', 'Descartado')
                                    ->orderBy('nombre', 'ASC');
        }
        if ($table == 'rectificados') {
            $result['tipo']     = 'warning';
            $result['titulo']   = 'Talleres Rectificadores';

            $result['data']     = $this->taller($request->get('grupo'))->where('rectificado', 1)
                                    ->orderBy('nombre', 'ASC');
        }
        if ($table == 'arregla_motos') {
            $result['tipo']     = 'danger';
            $result['titulo']   = 'Talleres Arregla Motos';

            $result['data']     = $this->taller($request->get('grupo'))->where('arregla_motos', 1)
                                    ->orderBy('nombre', 'ASC');
        }

        if ($request->has('zona')) {

            $result['data'] = $result['data']->where($field_zona, $request->get('zona'));
        }
        if ($request->has('distrito')) {

            $result['data'] = $result['data']->where($field_distrito, $request->get('distrito'));
        }

        $result['data'] = $result['data']->get();

        return $result;
    }

    /**
     * Agrega un limite de Zona si el usuario es un Ejecutivo
     *
     * @param DB $query
     * @return DB
     */
    private function checkEjecutivo($query, $grupo_id = null, $campo = 't.zona_id')
    {
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

            $query->whereIn($campo, $zonas->toArray());
        }

        return $query;
    }

    protected function taller($grupo_id = null)
    {
        return $this->checkEjecutivo( Taller::activo(), $grupo_id, 'zona_id' );
    }

    protected function premioTaller($grupo_id = null)
    {
        return $this->checkEjecutivo( Premio::canjeTalleres(), $grupo_id );
    }

    protected function empaqueTaller($grupo_id = null)
    {
        return $this->checkEjecutivo( Empaque::canjeTalleres(), $grupo_id );
    }

    protected function programacionTaller($grupo_id = null)
    {
        return $this->checkEjecutivo( Programacion::visitaTalleres(), $grupo_id );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data       = $request->all();
        $validator  = $this->registrar->validator($data);

        $mensaje['tipo']    = 'success';
        $mensaje['texto']   = "Se registro al usuario correctamente";

        if ($validator->fails())
        {
            $mensaje['tipo']    = 'warning';
            $mensaje['texto'] = $validator->messages()->first();
            return ['mensaje'=>$mensaje, 'success'=>false];
        }

        $data = $this->registrar->create($data);

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
        return User::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $data = Request::all();

        $validator  = $this->registrar->validator($data);

        if ($validator->fails())
        {
            return ['data'=>$validator->messages(), 'success'=>false];
        }

        try {
            $user = User::find($id);
            $user->nombre = $data['nombre'];
            $user->apellidos = $data['apellidos'];
            $user->cargo = $data['cargo'];
            $user->rol_id = $data['rol_id'];
            $user->telefono = $data['telefono'];
            $user->celular = $data['celular'];

            $user->save();
        } catch (\Exception $e) {
            return ['data'=>$e->getMessage(), 'success'=>false];
        }

        return ['data'=>$data, 'success'=>true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->activo = false;

            $user->save();
        } catch (\Exception $e) {
            return ['data'=>$e->getMessage(), 'success'=>false];
        }

        return ['success'=>true];
    }

}
