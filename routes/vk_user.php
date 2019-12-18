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

/**
 * @var Router $router
 */

use Laravel\Lumen\Routing\Router;

$router->post('auth', 'MeController@user');

$router->get('demo', 'DemoController@demo');
$router->get('about', 'DemoController@about');
$router->get('levels', 'LevelController@index');

$router->post('/me/start-at', 'MeController@setStartAt');
$router->post('/me/end-at', 'MeController@setEndAt');

$router->post('/me/start', 'MeController@start');
$router->post('/me/stop', 'MeController@stop');

$router->post('/me/settings', 'MeController@updateSettings');
