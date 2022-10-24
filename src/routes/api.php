<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ["check.api-auth","check.flood-protect"], 'prefix' => 'v1'], function () {
    Route::get('file/upload/', ['as' => 'apirest.upload', 'uses' => 'UploadController@list']);
    Route::post('file/upload/', ['as' => 'apirest.upload', 'uses' => 'UploadController@create']);
    Route::post('file/upload/multiple/', ['as' => 'apirest.upload', 'uses' => 'UploadController@createMultiple']);
    Route::get('file/upload/{id}', ['as' => 'apirest.delete', 'uses' => 'UploadController@show']);
    Route::delete('file/upload/{id}', ['as' => 'apirest.delete', 'uses' => 'UploadController@delete']);
});
