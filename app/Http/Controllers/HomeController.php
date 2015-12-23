<?php namespace App\Http\Controllers;

use MongoLite\Client as MongoClient;

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
     *
     * @param MongoClient $mongolite
     */
    public function __construct(MongoClient $mongolite)
    {
        $this->mongolite = $mongolite;
        $this->middleware('auth', ['except'=>['index', 'login', 'recovery', 'reset']]);
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        // $client     = new MongoClient(base_path().'/database');
        $database   = $this->mongolite->bugsy;
        $collection = $database->products;

        // $entry = ["name"=>"Super cool Product", "price"=>rand(10,100), "slug"=>str_slug("Supeñasdñe{{}{}123{}{qwe{}-qañañañañóaííuáua´:uüär cool Product", '-')];

        // $collection->insert($entry);

        $products = $collection->find(); // Get Cursor

        dump($products->toArray());
        return view('home');
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
