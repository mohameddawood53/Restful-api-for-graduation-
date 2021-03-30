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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(["prefix" => "v1"], function (){
    Route::post("/register" , [App\Http\Controllers\API\UserController::class, "register"]);
    Route::post("/login" , [App\Http\Controllers\API\UserController::class, "login"]);
    Route::group(["middleware" => "auth:api"], function ()
    {
        Route::post("/show" , [App\Http\Controllers\API\UserController::class, "show"]);
        Route::post("/deluser" , [App\Http\Controllers\API\UserController::class, "deluser"]);
        Route::get("/logout/{id}" , [App\Http\Controllers\API\UserController::class, "logout"]);
        // insert user mix
        Route::post("/user_mix" , [App\Http\Controllers\API\UserController::class, "userMix"]);

        //show mix
        Route::get("/user_mix/{id}" , [App\Http\Controllers\API\UserController::class, "user_mix"]);
        // update data of user mix
        Route::post("/update_mix" , [App\Http\Controllers\API\UserController::class, "update_mix"]);
        // insert new post
        Route::post("/newpost",[App\Http\Controllers\API\UserController::class, "newpost"]);
        Route::post("/newdonation",[App\Http\Controllers\API\UserController::class, "newdonation"]);
        Route::post("/newcomment",[App\Http\Controllers\API\UserController::class, "newcomment"]);
        Route::post("/viewPostCmnts",[App\Http\Controllers\API\UserController::class, "viewPostCmnts"]);
        Route::post("/delPost",[App\Http\Controllers\API\UserController::class, "delPost"]);
        Route::post("/showComment",[App\Http\Controllers\API\UserController::class, "showComment"]);
        Route::post("/addreply",[App\Http\Controllers\API\UserController::class, "addreply"]);
        Route::post("/delreplpy",[App\Http\Controllers\API\UserController::class, "delreplpy"]);
        Route::post("/delcmnt",[App\Http\Controllers\API\UserController::class, "delcmnt"]);
    });



});
