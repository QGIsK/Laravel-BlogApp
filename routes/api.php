<?php

use Illuminate\Http\Request;

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

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('users', 'UserController@index')->middleware('isAdmin');
    Route::get('users/{user}', 'UserController@show')->middleware('isAdminOrSelf');
});

Route::prefix('post')->group(function () {
    Route::get('/', 'PostController@index');
    Route::get('/{post}', 'PostController@show');

    Route::group(['middleware' => ['auth:api', 'isAdmin']], function () {
        Route::post('/', 'PostController@store');
        Route::put('/{post}', 'PostController@update');
        Route::delete('/{post}', 'PostController@destroy');
    });
});

Route::prefix('comment')->group(function () {
    Route::get('/{post}', 'CommentController@index');
    // Route::get('/{post}/{comment}', 'CommentController@show');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/{post}', 'CommentController@store');
    });

    Route::group(['middleware' => ['auth:api', 'isAdminOrSelf']], function () {
        Route::put('/{comment}', 'CommentController@update');
        Route::delete('/{comment}', 'CommentController@destroy');
    });
});
