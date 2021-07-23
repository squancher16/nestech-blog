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

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');

Route::get('posts/others','PostsController@get_others_posts')->middleware('auth:api');
Route::get('posts/my','PostsController@get_my_posts')->middleware('auth:api');

Route::apiResource('posts', 'PostsController')->middleware('auth:api');
Route::apiResource('comments', 'CommentsController')->middleware('auth:api');

