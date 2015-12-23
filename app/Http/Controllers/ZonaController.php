<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Zona;
use Auth;

use Illuminate\Http\Request;

class ZonaController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$zonas = Zona::where('activo', 1);

		if ( Auth::user()->esEjecutivo() ) {
			$zonas = $zonas->where('grupo_id', Auth::user()->grupo->id);
		}

		return $zonas->get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function distritos($id, Request $request)
	{
		return Zona::with('distritos')->find($id)->distritos;
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
