<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Grupo;
use Auth;

class GrupoController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $grupos = Grupo::where('activo', 1);

        if ( Auth::user()->esEjecutivo() ) {
            $grupos = $grupos->where('id', Auth::user()->grupo->id);
        }

        return $grupos->get();
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
            Grupo::create($request->all());
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
        return Grupo::find($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function zonas($id, Request $request)
    {
        return Grupo::with('zonas')->find($id)->zonas;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
