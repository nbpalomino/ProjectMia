<?php namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use MongoLite\Client as MongoClient;


class BugController extends Controller {

    /**
     * @var MongoClient
     */
    private $mongolite;

    /**
     * BugController construct method
     *
     * @param MongoClient $mongolite
     */
    public function __construct(MongoClient $mongolite)
    {
        $this->mongolite    = $mongolite;
        $this->db           = $mongolite->bugsy;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $products = $this->db->products;
        return $products->find()->toArray();
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
