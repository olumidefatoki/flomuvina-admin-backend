<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

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


Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});


Route::group(['prefix' => 'admin', 'middleware' => 'auth:api'], function () {

    Route::get('/hello', function () {
        $response = [
            'created_at' => Carbon::parse('2022-08-02 16:14:51')->diffForHumans(),
            'message' => 'Hello World',
            'user' => auth()->user(),
        ];
        return response($response, 200);
    });

});
