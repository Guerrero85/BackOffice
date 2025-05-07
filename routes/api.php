<?php

use App\Helpers\VersionHelper;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Rutas de la API versión 1
Route::group(['prefix' => VersionHelper::routePrefix()], function () 
{ // Usa la versión por defecto (V1)
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::post('/', 'store');
    });
    Route::get('/test', function() {
        return response()->json(['message' => 'API works!']);
    });
});
