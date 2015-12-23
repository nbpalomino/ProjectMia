<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Foto;
use App\Taller;
use Storage;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Filesystem\Filesystem;

use Illuminate\Http\Request;

class FotoController extends Controller {

	const 		TIMELINE_PATH =  '/assets/img/timeline/';

	private 	$auth;
	private 	$storage;
	protected 	$ruta_publica;

	public function __construct(Guard $auth, Filesystem $storage)
	{
		$this->auth 	= $auth;
		$this->storage 	= $storage;

		$this->ruta_publica = self::TIMELINE_PATH . $this->auth->user()->id . '/';
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		return Foto::where('created_by', $this->auth->user()->id)->orderBy('created_at', 'DESC')->get();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$mensaje['tipo'] 	= 'warning';
		$mensaje['texto'] 	= 'La imagen subida no es válida';

		$trimNombre = function($nombre){
			return str_replace(' ', '_', $nombre);
		};

		if ($request->file('foto')->isValid())
		{
			$foto = $request->file('foto');

			$fotoOriginalName = $trimNombre($foto->getClientOriginalName());

			$data = ['ruta'=>$this->ruta_publica.$fotoOriginalName, 'tipo'=>$foto->getClientMimeType()];

			try {
				$ruta = public_path().$this->ruta_publica;

				// Crear carpeta si no existe
				if ( !$this->storage->exists($ruta) ) {
					$this->storage->makeDirectory($ruta);
				}

				$ruta .= $fotoOriginalName;
				$this->storage->put($ruta, file_get_contents($foto));

				// Si todo esta bien creamos el registro de la foto
				Foto::create($data);

				$mensaje['tipo'] = 'success';
				$mensaje['texto'] = 'Se guardó correctamente la imagen';

			} catch (\Exception $e) {
				$mensaje['texto'] = 'Ocurrió un error al subir la imagen.'.$e->getMessage();
			}
		}

		return redirect("/#/fotosvideos?tipo={$mensaje['tipo']}");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return Foto::find($id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$mensaje['tipo'] = 'warning';
		$mensaje['tipo'] = 'Se eliminó la imagen elegida.';

		try {

			$foto = Foto::find($id);

			$this->storage->delete(public_path().$foto->ruta);

			$foto->delete();

			$mensaje['tipo'] = 'success';
		} catch (\Exception $e) {
			$mensaje['tipo'] = 'Ocurrió un error al eliminar la imagen.';
		}

		return redirect("/#/fotosvideos?tipo={$mensaje['tipo']}");
	}

}
