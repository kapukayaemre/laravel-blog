<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ArticleCommentController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LogController;
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

Route::prefix("admin")->middleware(['auth','verified'])->group(function () {

    Route::middleware("isAdmin")->group(function () {

        /*** File-manager package */
        Route::group(['prefix' => 'filemanager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });

        Route::get("logs-db", [LogController::class, "index"])->name("dbLogs");
        Route::get("logs-db/{id}", [LogController::class, "getLog"])->name("dblogs.getLog")->whereNumber("id");

        /*** Dashboard */
        Route::get('/', [AdminController::class, "index"])->name('admin.index');


        /*** Article Routes */
        Route::get("articles", [ArticleController::class, "index"])->name("article.index");
        Route::get("articles/create", [ArticleController::class, "create"])->name("article.create");
        Route::post("articles/create", [ArticleController::class, "store"]);
        Route::get("articles/{id}/edit", [ArticleController::class, "edit"])->name("article.edit");
        Route::post("articles/{id}/edit", [ArticleController::class, "update"]);
        Route::post("articles/change-status", [ArticleController::class, "changeStatus"])->name("article.changeStatus");
        Route::delete("articles/delete", [ArticleController::class, "delete"])->name("article.delete");


        /*** Article Comment Routes */
        Route::get("articles/pending-approval", [ArticleCommentController::class, "approvalList"])->name("article.pending-approval");
        Route::post("articles/pending-approval/change-status", [ArticleCommentController::class, "changeStatus"])->name("article.pending-approval.change-status");
        Route::get("articles/comment-list", [ArticleCommentController::class, "list"])->name("article.comment-list");
        Route::delete("articles-comment/delete", [ArticleCommentController::class, "delete"])->name("comment.delete");
        Route::post("articles-comment/restore", [ArticleCommentController::class, "restore"])->name("comment.restore");


        /*** Categories Routes */
        Route::get("categories", [CategoryController::class, "index"])->name("category.index");
        Route::get("categories/create", [CategoryController::class, "create"])->name("category.create");
        Route::post("categories/create", [CategoryController::class, "store"]);
        Route::post("categories/change-status", [CategoryController::class, "changeStatus"])->name("category.changeStatus");
        Route::post("categories/change-feature-status", [CategoryController::class, "changeFeatureStatus"])->name("category.changeFeatureStatus");
        Route::post("categories/delete", [CategoryController::class, "delete"])->name("category.delete");
        Route::get("categories/{id}/edit", [CategoryController::class, "edit"])->name("category.edit")->whereNumber('id');
        Route::post("categories/{id}/edit", [CategoryController::class, "update"])->whereNumber('id');


        /*** Settings Routes */
        Route::get("settings", [SettingsController::class, "show"])->name("settings");
        Route::post("settings", [SettingsController::class, "update"]);


        /*** User Routes */
        Route::get("users", [UserController::class, "index"])->name("user.index");
        Route::get("users/create", [UserController::class, "create"])->name("user.create");
        Route::post("users/create", [UserController::class, "store"]);
        Route::post("users/change-status", [UserController::class, "changeStatus"])->name("user.changeStatus");
        Route::post("users/change-is-admin", [UserController::class, "changeIsAdmin"])->name("user.changeIsAdmin");
        Route::get("users/{user:username}/edit", [UserController::class, "edit"])->name("user.edit")->whereNumber('id');
        Route::post("users/{user:username}/edit", [UserController::class, "update"])->whereNumber('id');
        Route::delete("users/delete", [UserController::class, "delete"])->name("user.delete");
        Route::post("users/restore", [UserController::class, "restore"])->name("user.restore");
    });

    /*** Article and Article Comment Favorite Routes */
    Route::post("articles/favorite", [ArticleController::class, "favorite"])->name("article.favorite");
    Route::post("articles-comment/favorite", [ArticleCommentController::class, "favorite"])->name("comment.favorite");


    /*** Email Routes */
    Route::get("email-themes", [EmailController::class, "themes"])->name("admin.email-themes.index");
    Route::get("email-themes/create", [EmailController::class, "create"])->name("admin.email-themes.create");
    Route::post("email-themes/create", [EmailController::class, "store"]);
    Route::get("email-themes/edit", [EmailController::class, "edit"])->name("admin.email-themes.edit");
    Route::post("email-themes/edit", [EmailController::class, "update"]);
    Route::delete("email-themes/delete", [EmailController::class, "delete"])->name("admin.email-themes.delete");
    Route::post("email-themes/change-status", [EmailController::class, "changeStatus"])->name("admin.email-themes.changeStatus");

    Route::get("email-themes/assign-list", [EmailController::class, "assignList"])->name("admin.email-themes.assign-list");
    Route::get("email-themes/assign-list/show-email", [EmailController::class, "showEmail"])->name("admin.email-themes.assign.show.email");
    Route::delete("email-themes/assign-list/delete", [EmailController::class, "assignDelete"])->name("admin.email-themes.assign.delete");
    Route::get("email-themes/assign", [EmailController::class, "assignShow"])->name("admin.email-themes.assign");
    Route::post("email-themes/assign", [EmailController::class, "assign"]);
    Route::get("email-themes/assign/get-theme", [EmailController::class, "assignGetTheme"])->name("admin.email-themes.assign.getTheme");


});

/*** Admin Panel Login */
Route::get("admin/login", [LoginController::class, "showLogin"])->name("login");
Route::post("admin/login", [LoginController::class, "login"]);


/*** Frontend Routes */
Route::get('/', [FrontController::class, "home"])->name('home');
Route::get("articles", [FrontController::class, "articleList"])->name("front.articleList");
Route::get('/categories/{category:slug}', [FrontController::class, "category"])->name('front.categoryArticles');
Route::get('/authors/{user:username}', [FrontController::class, "authorArticles"])->name('front.authorArticles');
Route::get('/@{user:username}/{article:slug}', [FrontController::class, "articleDetail"])->name('front.articleDetail')->middleware("visitedArticle");
Route::post("/{article:id}/article-comment", [FrontController::class, "articleComment"])->name("article.comment");
Route::get("/search", [FrontController::class, "search"])->name("front.search");


Route::post("/logout", [LoginController::class, "logout"])->name("logout");

Route::get("register", [LoginController::class, "showRegister"])->name("register");
Route::post("register", [LoginController::class, "register"]);

Route::get("/login", [LoginController::class, "showLoginUser"])->name("user.login");
Route::post("/login", [LoginController::class, "loginUser"]);

Route::post("/contact", [LoginController::class, ""])->name("contact");

Route::get("/reset-password", [LoginController::class, "showPasswordReset"])->name("passwordReset");
Route::post("/reset-password", [LoginController::class, "sendPasswordReset"]);
Route::get("/reset-password/{token}", [LoginController::class, "showPasswordResetConfirm"])->name("passwordResetToken");
Route::post("/reset-password/{token}", [LoginController::class, "passwordReset"]);


Route::get("/auth/verify/{token}", [LoginController::class, "verify"])->name("verify-token");
Route::get("/auth/{driver}/callback", [LoginController::class, "socialVerify"])->name("socialVerify");
Route::get("/auth/{driver}", [LoginController::class, "socialLogin"])->name("socialLogin");

