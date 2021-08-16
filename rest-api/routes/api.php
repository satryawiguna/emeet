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

Route::post('/register', 'Api\RegisterController@actionRegister')
    ->name('register');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'Api\AuthController@actionLogin')
        ->name('auth.login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', 'Api\AuthController@actionLogout')
            ->name('auth.logout');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'user'], function () {
    Route::group(['prefix' => 'contact'], function () {
        Route::get('/', 'Api\UserController@actionViewAllUserContact')
            ->name('user.contacts');
        Route::delete('/destroy/{id}', 'Api\UserController@actionUserContactDestroy')
            ->name('user.contact.delete');
        Route::delete('/destroy', 'Api\UserController@actionUserContactBulkDestroy')
            ->name('user.contact.bulk.delete');

        Route::group(['prefix' => 'me'], function () {
            Route::put('/', 'Api\UserController@actionUserContactMeUpdate')
                ->name('user.contact.me');
        });
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'tag'], function () {
    Route::get('/', 'Api\TagController@actionViewAllTag')
        ->name('tags');
    Route::post('/', 'Api\TagController@actionStoreTag')
        ->name('tag.store');
    Route::get('/{id}', 'Api\TagController@actionViewTag')
        ->name('tag.show');
    Route::put('/{id}', 'Api\TagController@actionUpdateTag')
        ->name('tag.update');
    Route::delete('/{id}', 'Api\TagController@actionDestroyTag')
        ->name('tag.delete');

    Route::group(['prefix' => 'bulk'], function () {
        Route::delete('/delete', 'Api\TagController@actionDestroyBulkTag')
            ->name('tag.bulk.delete');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'blog-category'], function () {
    Route::get('/', 'Api\PostController@actionViewAllBlogCategory')
        ->name('blog-categories');
    Route::post('/', 'Api\PostController@actionStoreBlogCategory')
        ->name('blog-category.store');
    Route::get('/{id}', 'Api\PostController@actionViewBlogCategory')
        ->name('blog-category.show');
    Route::put('/{id}', 'Api\PostController@actionUpdateBlogCategory')
        ->name('blog-category.update');
    Route::delete('/{id}', 'Api\PostController@actionDestroyBlogCategory')
        ->name('blog-category.delete');

    Route::group(['prefix' => 'bulk'], function () {
        Route::delete('/delete', 'Api\PostController@actionDestroyBulkBlogCategory')
            ->name('blog-category.bulk.delete');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'blog'], function () {
    Route::get('/', 'Api\PostController@actionViewAllBlog')
        ->name('blogs');
    Route::post('/', 'Api\PostController@actionStoreBlog')
        ->name('blog.store');
    Route::get('/{id}', 'Api\PostController@actionViewBlog')
        ->name('blog.show');
    Route::put('/{id}', 'Api\PostController@actionUpdateBlog')
        ->name('blog.update');
    Route::delete('/{id}', 'Api\PostController@actionDestroyBlog')
        ->name('blog.delete');

    Route::group(['prefix' => 'bulk'], function () {
        Route::delete('/delete', 'Api\PostController@actionDestroyBulkBlog')
            ->name('blog.bulk.delete');
    });

    Route::group(['prefix' => '{id}'], function () {
        Route::post('/comments', 'Api\PostController@actionStoreComment')
            ->name('blog.id.comment.store');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'page'], function () {
    Route::get('/', 'Api\PageController@actionViewAllPage')
        ->name('pages');
    Route::post('/', 'Api\PageController@actionStorePage')
        ->name('page.store');
    Route::get('/{id}', 'Api\PageController@actionViewPage')
        ->name('page.show');
    Route::put('/{id}', 'Api\PageController@actionUpdatePage')
        ->name('page.update');
    Route::delete('/{id}', 'Api\PageController@actionDestroyPage')
        ->name('page.delete');

    Route::group(['prefix' => 'bulk'], function () {
        Route::delete('/delete', 'Api\PageController@actionDestroyBulkPage')
            ->name('page.bulk.delete');
    });

    Route::group(['prefix' => '{id}'], function () {
        Route::post('/comments', 'Api\PageController@actionStoreComment')
            ->name('page.id.comment.store');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'comment'], function () {
    Route::get('/', 'Api\CommentController@actionViewAllComment')
        ->name('comments');
    Route::get('/{id}', 'Api\CommentController@actionViewComment')
        ->name('comment.show');
    Route::put('/{id}', 'Api\CommentController@actionUpdateComment')
        ->name('comment.update');
    Route::delete('/{id}', 'Api\CommentController@actionDestroyComment')
        ->name('comment.delete');

    Route::group(['prefix' => 'bulk'], function () {
        Route::delete('/delete', 'Api\CommentController@actionDestroyBulkComment')
            ->name('comment.bulk.delete');
    });
});

