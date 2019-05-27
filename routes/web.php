<?php

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
    return $router->app->version();
});

$router->post(
    'auth/login',
    [
        'uses' => 'AuthController@login'
    ]
);

$router->post(
    'auth/register',
    [
        'uses' => 'RegisterController@register'
    ]
);

$router->get(
    'auth/current',
    [
        'middleware' => 'auth',
        'uses' => 'UserController@current'
    ]
);

$router->get(
    '/users',
    [
        'middleware' => 'auth',
        'uses' => 'UserController@listOtherUsers'
    ]
);

$router->delete(
    '/users/{id}',
    [
        'middleware' => 'auth',
        'uses' => 'UserController@deleteUser'
    ]
);