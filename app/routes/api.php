<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiderInfoController;
use App\Http\Controllers\RestaurantInfoController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('riders', [RiderInfocontroller::class,'store']);
Route::post('restaurant', [RestaurantInfoController::class,'store']);
Route::get('rider/{id}',[RiderInfoController::class,'getRiders']);
