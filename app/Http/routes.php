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
Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin'], function () {
        Route::get('/', 'Admin\HomeController@index');
        /*-----------------------------User-------------------------------------*/
        Route::group(['prefix' => 'user', 'middleware' => 'function:user'], function () {
            Route::get('/', 'Admin\UserController@listUser');
            Route::post('/add', 'Admin\UserController@doAddUser');
            Route::get('/load', 'Admin\UserController@loadUser');
            Route::post('/edit', 'Admin\UserController@doEditUser');
            Route::post('/delete', 'Admin\UserController@doDeleteUser');
        });
        /*-----------------------------Permission-------------------------------------*/
        Route::group(['prefix' => 'permission', 'middleware' => 'function:permission'], function () {
            Route::group(['prefix' => 'group'], function () {
                Route::get('/', 'Admin\PermissionController@listGroup');
                Route::get('/add', 'Admin\PermissionController@addGroup');
                Route::post('/add', 'Admin\PermissionController@doAddGroup');
                Route::get('/edit/{group_id}', 'Admin\PermissionController@editGroup');
                Route::post('/edit/{group_id}', 'Admin\PermissionController@doEditGroup');
                Route::post('/delete', 'Admin\PermissionController@doDeleteGroup');
            });
            Route::group(['prefix' => 'function'], function () {
                Route::get('/', 'Admin\PermissionController@listFunction');
                Route::post('/add', 'Admin\PermissionController@doAddFunction');
                Route::get('/load', 'Admin\PermissionController@loadFunction');
                Route::post('/edit', 'Admin\PermissionController@doEditFunction');
                Route::post('/delete', 'Admin\PermissionController@doDeleteFunction');
            });
        });
        /*-----------------------------Article-------------------------------------*/
        Route::group(['prefix' => 'article'], function () {
            Route::get('/', 'Admin\ArticleController@listArticle');
            Route::get('/add', 'Admin\ArticleController@addArticle');
            Route::post('/add', 'Admin\ArticleController@doAddArticle');
            Route::get('/tags-input', 'Admin\ArticleController@tagsInput');
            Route::get('/edit/{article_id}', 'Admin\ArticleController@editArticle');
            Route::post('/edit/{article_id}', 'Admin\ArticleController@doEditArticle');
            Route::post('/image-upload', 'Admin\ArticleController@doHandleImage');
            Route::post('/handle-content', 'Admin\ArticleController@doHandleContent');
            Route::post('/delete', 'Admin\ArticleController@doDeleteArticle');
            Route::group(['prefix' => 'category'], function () {
                Route::get('/', 'Admin\ArticleController@listCategory');
                Route::post('/add', 'Admin\ArticleController@doAddCategory');
                Route::get('/load', 'Admin\ArticleController@loadCategory');
                Route::post('/edit', 'Admin\ArticleController@doEditCategory');
                Route::post('/delete', 'Admin\ArticleController@doDeleteCategory');
            });
        });
        Route::get('/str-slug', 'Admin\HomeController@slug');
        Route::post('/image-upload', 'Admin\HomeController@uploadImage');
    });
    Route::get('/login', 'Admin\AccessController@login');
    Route::post('/login', 'Admin\AccessController@doLogin');
    Route::get('/logout', 'Admin\AccessController@logout');
});