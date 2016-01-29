<?php namespace App\Http\Controllers;

use App\Category;
use App\Domain\Repository\Interfaces\IProductRepository;
use App\Product;
use Illuminate\Http\Response;

class HomeController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     * @param IProductRepository $repo
     */
    public function __construct(IProductRepository $repo)
    {
        $this->repo = $repo;
        //$this->middleware('auth', ['except'=>['index', 'login', 'recovery', 'reset']]);
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $data['products'] = $this->repo->findAll();

        return view('home')->with($data);
    }

    /**
     * Show the application login to the user.
     *
     * @return Response
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Show the application password recovery to the user.
     *
     * @return Response
     */
    public function recovery()
    {
        return view('auth.password');
    }

    /**
     * Show the application password reset to the user.
     *
     * @return Response
     */
    public function reset()
    {
        return view('auth.reset');
    }
}
