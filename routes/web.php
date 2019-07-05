<?php

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

Route::get("/", "StaticPagesController@home")->name('home');
Route::get("/about", "StaticPagesController@about")->name('about');
Route::get("/help", "StaticPagesController@help")->name("help");

Route::get("/signup", "UsersController@create")->name("signup");
Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');

Route::post("/users/follow/{user}", "FollowersController@store")->name("followers.store");
Route::delete("/users/unfollow/{user}", "FollowersController@destroy")->name("followers.destroy");

Route::get("/users/{user}/{token}", "UsersController@confirmEmail")->name("confirm_email");
Route::resource("users", "UsersController")->names("users");

Route::get("/login", "SessionsController@create")->name("login");
Route::post("/login", "SessionsController@store")->name("login");
Route::delete("/logout", "SessionsController@destroy")->name("logout");

Route::get("/password/reset", "Auth\ForgotPasswordController@showLinkRequestForm")->name("password.request");
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::resource("statuses", "StatusesController")->only("store", "destroy");





Route::get("/test/db", function () {
    dd(DB::table("users")->count());
});