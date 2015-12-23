<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');
Route::get('/login', 'HomeController@login');
Route::get('/recovery', 'HomeController@recovery');
Route::get('/reset', 'HomeController@reset');


Route::resource('/bugsy', 'BugController');

// Route::group(['prefix'=>'api'], function($exception){

//     Route::group(['middleware'=>['auth', 'cliente']], function(){
//         //Listado de Registro de Visitas y Canjes
//         Route::get('/programacion/fechavisita/{fecha}', 'ProgramacionController@fechaVisita');
//         Route::post('/programacion/{id}/taller/{taller}/fechavisita/{fecha}', 'ProgramacionController@updateVisita');
//         Route::post('/programacion/{id}/duplicado', 'ProgramacionController@duplicate');
//         Route::post('/programacion/taller', 'ProgramacionController@taller');
//         //---

//         //Listado de Programacion
//         Route::any('/programacion/{id}/fechavisita/{fecha}', 'ProgramacionController@visitas');

//         //Modal de Canjes de Empaques y Premios
//         Route::get('/taller/{id}/empaques', 'TallerController@empaques');
//         Route::get('/taller/{id}/premios', 'TallerController@premios');
//         Route::post('/taller/{id}/empaques', 'TallerController@saveEmpaques');
//         Route::post('/taller/{id}/premios', 'TallerController@savePremios');
//         //---

//         //Listado de Distritos
//         Route::get('/zona/{id}/distritos', 'ZonaController@distritos');

//         //Listado de Zonas
//         Route::get('/grupo/{id}/zonas', 'GrupoController@zonas');

//         //Dashboard
//         Route::get('/user/dashboard', 'UserController@dashboard');

//         // Consolidados
//         Route::get('/consolidado', 'ConsolidadoController@index');
//         Route::get('/consolidado/ruta-diaria', 'ConsolidadoController@rutaDiaria');
//         Route::get('/consolidado/new', 'ConsolidadoController@create');

//         Route::resource('/distrito', 'DistritoController');
//         Route::resource('/empaque', 'EmpaqueController');
//         Route::resource('/grupo', 'GrupoController');
//         Route::resource('/premio', 'PremioController');
//         Route::resource('/programacion', 'ProgramacionController');
//         Route::resource('/promotor', 'PromotorController');
//         Route::resource('/taller', 'TallerController');
//         Route::resource('/user', 'UserController');
//         Route::resource('/foto', 'FotoController');
//         Route::resource('/zona', 'ZonaController');
//     });

//     Route::controllers([
//         '/auth' => 'Auth\AuthController',
//         '/password' => 'Auth\PasswordController',
//     ]);

// });

// App::missing(function($exception) {
//     return View::make('app');
// });

