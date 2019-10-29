<?php

use Illuminate\Http\Request;
use App\User;

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

Route::apiResource('/dashboarduser', 'DashBoardUserController');
Route::apiResource('/location', 'LocationController');
Route::apiResource('/comment', 'CommentController');
Route::apiResource('/tantouUser', 'TantouUserController');
Route::apiResource('/tantou', 'TantouController');

Route::get('dashboarduser/tag/{comentNum}', 'DashBoardUserController@tag');
Route::put('/gomi'          , 'DashBoardUserController@gomi');
Route::put('/souzi'         , 'DashBoardUserController@souzi');
Route::put('/seisou'        , 'DashBoardUserController@seisou');
Route::put('/hinomoto'       , 'DashBoardUserController@hinomoto');
Route::put('/server'        , 'DashBoardUserController@server');

Route::patch('/zaiseki/{id}'  , 'DashBoardUserController@zaiseki');
Route::patch('/taiseki/{id}'  , 'DashBoardUserController@taiseki');

Route::group([

    'prefix' => 'auth'

], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('signup', 'AuthController@signup');
    Route::post('me', 'AuthController@me');
    Route::post('payload', 'AuthController@payload');

});