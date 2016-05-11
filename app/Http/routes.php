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

$app->get('/', function () use ($app) {
    return $app->version();
});
//$app->get('groups', 'GroupController@index');'middleware' => 'logRequest'

$app->group(['prefix' => 'faceApi', 'namespace' => 'App\Http\Controllers', 
    'middleware' => ['apiClient', 'logRequest']],
    function () use ($app) {

        $app->post('groups', 'GroupController@store');
        $app->get('groups', 'GroupController@index');
        $app->get('groups/{id}', 'GroupController@get');
        $app->delete('groups/{id}', 'GroupController@destroy');

        $app->post('persons', 'PersonController@store');
        $app->get('persons/{id}', 'PersonController@get');
        $app->delete('persons/{id}', 'PersonController@destroy');
            
        $app->post('test', 'FaceController@socket'); //testing-working
        $app->post('persons/faces', 'FaceController@store'); //working
        $app->delete('persons/faces/{id}', 'FaceController@destroy');
        
        $app->post('detect', 'FaceController@detect');
        $app->post('verify/{personId}', 'RecognitionController@verify'); //working
        $app->post('recognize/{groupId}', 'RecognitionController@recognize'); //working
        $app->post('compare', 'RecognitionController@compare'); //working
    }
);
