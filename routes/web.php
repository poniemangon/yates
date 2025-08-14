<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\CategoriesController;
use App\Http\Controllers\Backend\ArticlesController;
use App\Http\Controllers\Backend\TagsController;

use App\Http\Controllers\Frontend\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('ssy-administration')->group(function() {
    Route::group(['middleware' => 'prevent-back-history'], function() {




	    //auth
	    Route::get('/', [UserController::class, 'login'])->name('/');
	    Route::post('user-login', [UserController::class, 'loginUser'])->name('user-login');
		Route::get('logout', [UserController::class, 'logout'])->name('logout');

		//users
	    Route::get('users-list', [UserController::class, 'list'])->name('users-list');
	    Route::get('register-user', [UserController::class, 'register'])->name('register-user');
	    Route::post('register-user', [UserController::class, 'registerUser'])->name('register-user');
	    Route::get('edit-user/{userId}', [UserController::class, 'edit'])->name('edit-user');
	    Route::post('edit-user/{userId}', [UserController::class, 'editUser'])->name('edit-user');
	    Route::delete('delete-user/{userId}', [UserController::class, 'deleteUser'])->name('delete-user');

	    //categories
	    Route::get('categories-list', [CategoriesController::class, 'list'])->name('categories-list');
	    Route::get('register-category', [CategoriesController::class, 'register'])->name('register-category');
	    Route::post('register-category', [CategoriesController::class, 'registerCategory'])->name('register-category');
	    Route::get('edit-category/{categoryId}', [CategoriesController::class, 'edit'])->name('edit-category');
	    Route::post('edit-category/{categoryId}', [CategoriesController::class, 'editCategory'])->name('edit-category');
	    Route::delete('delete-category/{categoryId}', [CategoriesController::class, 'deleteCategory'])->name('delete-category');

	    //articles
		Route::get('articles-list', [ArticlesController::class, 'list'])->name('articles-list');
		Route::get('register-article', [ArticlesController::class, 'register'])->name('register-article');
		Route::post('register-article', [ArticlesController::class, 'registerArticle'])->name('register-article');
		Route::get('edit-article/{articleId}', [ArticlesController::class, 'edit'])->name('edit-article');
		Route::post('edit-article/{articleId}', [ArticlesController::class, 'editArticle'])->name('edit-article');
		Route::delete('delete-article/{articleId}', [ArticlesController::class, 'deleteArticle'])->name('delete-article');

		//tags
		Route::get('tags-list', [TagsController::class, 'list'])->name('tags-list');
		Route::get('register-tag', [TagsController::class, 'register'])->name('register-tag');
		Route::post('register-tag', [TagsController::class, 'registerTag'])->name('register-tag');
		Route::get('edit-tag/{tagId}', [TagsController::class, 'edit'])->name('edit-tag');
		Route::post('edit-tag/{tagId}', [TagsController::class, 'editTag'])->name('edit-tag');
		Route::delete('delete-tag/{tagId}', [TagsController::class, 'deleteTag'])->name('delete-tag');
	});
});



