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


Route::get('/', function () {
    return view('welcome');
});


//Route::get('client', function(){
//	return \CodeProject\Client::all();
//} );
Route::post('oauth/access_token', function(){
    return Response::json(Authorizer::issueAccessToken());
});

//middleware fica entre a request e a response, assim ele valida o logon esta ok, se sim log, se nao erro
Route::group(['middleware'=>'oauth'], function(){

    route::resource('client', 'ClientController', ['except' => ['create','edit']]);
    //O resource acima correponde a juncao de todas rotas abaixo,
    // uso do except para evitar que crie rotas para alguns dos metodos automaticos do laravel que nao serao usados
    //Route::get('client', 'ClientController@index' );
    //Route::post('client', 'ClientController@store' );
    //Route::get('client/{id}', 'ClientController@show' );
    //Route::delete('client/{id}', 'ClientController@destroy' );
    //Route::put('client/{id}', 'ClientController@update' );

    //Essa rota de middleware deve estar registrada no app/Http/kernel.php
    //exemplo de uso do middleware para checar autorização de acesso via rotas
    //Route::group(['middleware'=>'CheckProjectOwner'], function(){

    route::resource('project', 'ProjectController', ['except' => ['create','edit']]);

    Route::group(['prefix'=>'project'], function(){


        Route::get('{id}/note', 'ProjectNoteController@index' );
        Route::post('{id}/note', 'ProjectNoteController@store' );
        Route::get('{id}/note/{noteId}', 'ProjectNoteController@show' );
        Route::put('{id}/note/{noteId}', 'ProjectNoteController@update' );
        Route::delete('{id}/note/{noteId}', 'ProjectNoteController@delete' );

        Route::get('{id}/task', 'ProjectTaskController@index' );
        Route::post('{id}/task', 'ProjectTaskController@store' );
        Route::get('{id}/task/{taskId}', 'ProjectTaskController@show' );
        Route::put('{id}/task/{taskId}', 'ProjectTaskController@update' );
        Route::delete('{id}/task/{taskId}', 'ProjectTaskController@delete' );

        Route::get('{id}/addMember/{memberId}', 'ProjectController@addMember' );
        Route::get('{id}/removeMember/{memberId}', 'ProjectController@removeMember' );
        Route::get('{id}/isMember/{memberId}', 'ProjectController@isMember' );
    });

});


