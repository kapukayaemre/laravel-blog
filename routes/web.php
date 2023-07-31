<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix("admin")->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('admin.index');

    Route::get("articles", [ArticleController::class, "index"])->name("article.index");
    Route::get("articles/create", [ArticleController::class, "create"])->name("article.create");
    Route::post("articles/create", [ArticleController::class, "store"]);
    Route::get("articles/{id}/edit", [ArticleController::class, "edit"])->name("article.edit");
    Route::post("articles/{id}/edit", [ArticleController::class, "update"]);
    Route::post("articles/change-status", [ArticleController::class, "changeStatus"])->name("article.changeStatus");
    Route::delete("articles/delete", [ArticleController::class, "delete"])->name("article.delete");


    Route::get("categories", [CategoryController::class, "index"])->name("category.index");
    Route::get("categories/create", [CategoryController::class, "create"])->name("category.create");
    Route::post("categories/create", [CategoryController::class, "store"]);
    Route::post("categories/change-status", [CategoryController::class, "changeStatus"])->name("category.changeStatus");
    Route::post("categories/change-feature-status", [CategoryController::class, "changeFeatureStatus"])->name("category.changeFeatureStatus");
    Route::post("categories/delete", [CategoryController::class, "delete"])->name("category.delete");
    Route::get("categories/{id}/edit", [CategoryController::class, "edit"])->name("category.edit")->whereNumber('id');
    Route::post("categories/{id}/edit", [CategoryController::class, "update"])->whereNumber('id');


    Route::get("settings", [SettingsController::class, "show"])->name("settings");
    Route::post("settings", [SettingsController::class, "update"]);


    Route::get("users", [UserController::class, "index"])->name("user.index");
    Route::get("users/create", [UserController::class, "create"])->name("user.create");
    Route::post("users/create", [UserController::class, "store"]);
    Route::post("users/change-status", [UserController::class, "changeStatus"])->name("user.changeStatus");
    Route::get("users/{id:users}/edit", [UserController::class, "edit"])->name("user.edit")->whereNumber('id');
    Route::post("users/{id:users}/edit", [UserController::class, "update"])->whereNumber('id');
    Route::post("users/delete", [UserController::class, "delete"])->name("user.delete");


});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::get('/', [FrontController::class, "home"])->name('home');
Route::get('/categories/{slug:categories}', [FrontController::class, "category"])->name('front.category');
Route::get('/{username:users}/{slug:articles}', [FrontController::class, "articleDetail"])->name('front.articleDetail');

Route::get("/login", [LoginController::class, "showLogin"])->name("login");
Route::post("/login", [LoginController::class, "login"]);
Route::post("/logout", [LoginController::class, "logout"])->name("logout");

Route::get("/register", [LoginController::class, "showRegister"])->name("register");
Route::post("/register", [LoginController::class, "register"]);
