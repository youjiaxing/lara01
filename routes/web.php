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
Route::resource("users", "UsersController")->names("users");
Route::get("/test/db", function () {
    dd(DB::table("users")->count());
});