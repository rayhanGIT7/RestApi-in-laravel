<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users/{id?}',[UserApiController::class,'showUser']);
Route::post('/users-add',[UserApiController::class,'addUser']);
Route::post('/users-multiple-add',[UserApiController::class,'addMultipleUser']);
Route::put('/users-update/{id}',[UserApiController::class,'userUpdate']);
Route::delete('/users-delete/{id}',[UserApiController::class,'userDelete']);
Route::delete('/users-multiple-delete/{ids}',[UserApiController::class,'userMultipleDelete']);
