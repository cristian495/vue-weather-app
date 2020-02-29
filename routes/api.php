<?php

use Illuminate\Http\Request;
use Zttp\Zttp;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::get('/weather/', function (Request $request) {
Route::get('/weather/{lat}/{lng}', function (Request $request) {

    $apiKey = config('services.darksky.key');
    $lat = request()->lat;
    $lng = request()->lng;

    $response = Zttp::get("https://api.darksky.net/forecast/$apiKey/$lat,$lng?lang=es&units=ca");
    
    return $response->json();

});