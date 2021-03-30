<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get("/newcomment",[App\Http\Controllers\API\UserController::class, "index"]);

Route::post("/newcomment",[App\Http\Controllers\API\UserController::class, "newcomment"]);

