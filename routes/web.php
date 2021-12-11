<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return view('welcome');
});


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login')
    ;

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/logout', 'AuthController@logout');
        $router->get('/profile', 'UserController@show');


        $router->put('users/{user}/todos/{todo}/complete', [
            'uses' => 'TodoController@makeCompleted',
            'as' => 'users.tasks.complete',
        ]);

        $router->get('users/{user}/todos','TodoController@index');
        $router->post('users/{user}/todos','TodoController@store');
        $router->get('users/{user}/todos/{todo}','TodoController@show');
        $router->put('users/{user}/todos/{todo}','TodoController@update');
        $router->delete('users/{user}/todos/{todo}','TodoController@destroy');


    });
});
