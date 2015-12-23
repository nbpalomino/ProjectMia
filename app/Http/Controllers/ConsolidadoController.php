<?php namespace App\Http\Controllers;

use App\Http\Requests;
use PDF;
use Auth;
use DateTime;
use App\Taller;
use App\Premio;
use App\Empaque;
use App\Grupo;

use Illuminate\Http\Request;

class ConsolidadoController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['bosch']  = Empaque::activo()->where('marca', 'Bosch')->orderBy('valor', 'ASC')->get();
        $data['fm']     = Empaque::activo()->where('marca', 'FM')->orderBy('valor', 'ASC')->get();
        $data['premios']    = Premio::activo()->orderBy('valor', 'ASC')->get();

        $fechaInicio = $this->fecha($request->get('inicio'));
        $fechaFin    = $this->fecha($request->get('fin'));



        $data['rango'] = ( $fechaInicio ? $fechaInicio->format('d/m/Y') : '' )." | ".( $fechaFin ? $fechaFin->format('d/m/Y') : '' );

        $data['talleres']   =  $this->talleres()->with(['empaques', 'premios'])
                                ->whereHas('empaques', function($q) use ($fechaInicio, $fechaFin){

                                    if ( $fechaInicio ) {
                                        $q->where('fecha_visita', '>', $fechaInicio->format('Y-m-d'));
                                    }
                                    if ( $fechaFin ) {
                                        $q->where('fecha_visita', '<', $fechaFin->format('Y-m-d'));
                                    }

                                })->get();

        return PDF::loadView('reports.visitasCanjes', $data)->setPaper('a4')->setOrientation('landscape')->download();
        // return view('reports.visitasCanjes', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function rutaDiaria(Request $request)
    {
        $Grupo          = Grupo::find( $request->get('grupo_id') );
        $fechaVisita    = $this->fecha($request->get('fecha_visita'));

        $data['fecha_visita']   = $fechaVisita->format('d/m/Y');
        $data['grupo']          = $Grupo->nombre;
        $data['talleres']       = $this->talleres( $Grupo->id )->with(['visitas'])
                                    ->whereHas('visitas', function($q) use ($fechaVisita){

                                        $q->where('fecha_visita', $fechaVisita->format('Y-m-d'));

                                    })->get();

        return PDF::loadView('reports.rutaDiaria', $data)->setPaper('a4')->setOrientation('landscape')->download();
        // return view('reports.rutaDiaria', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    private function talleres($grupo_id = null)
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

    private function fecha($fecha)
    {
        return $fecha ? new DateTime(trim($fecha, '"'))    : null;
    }
}
