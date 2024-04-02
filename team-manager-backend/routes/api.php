<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Role Admin
    Route::middleware('user-access:admin') -> prefix('admin') -> group(function () {
    });

    // Role Manager
    Route::middleware('user-access:manager') -> prefix('manager') -> group(function () {
    });

    // Role User
    Route::middleware('user-access:user') -> group(function () {
        Route::get('/user', function (Request $request) {
            $array = [
                "foo" => "bar", 
                "baz" => "qux",
            ];
            
            $json = json_encode($array);
            return $json;
        });
    });
});
