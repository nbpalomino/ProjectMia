<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Promotor;
use Auth;

class PromotorController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$promotores = $this->promotores()->get()->map(function($promotor){
			$promotor->nombreGrupo = $promotor->grupo->nombre;
			return $promotor;
		});

		return $promotores;
	}

	protected function promotores()
	{
		$promotores = Promotor::where('activo', 1);

		if ( Auth::user()->esEjecutivo() && Auth::user()->grupo  ) {

            $promotores->where('grupo_id', Auth::user()->grupo->id);
        }

        return $promotores;
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
            Promotor::create($request->all());
        } catch (\Exception $e) {
            $mensaje['tipo']    = 'warning';
            $mensaje['texto']   = "Ocurrió un problema al registrar.".$e->getMessage();
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
		return Promotor::find($id);
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
