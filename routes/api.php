<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\ActivityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix("v1")->group(function () {

    // testing api
    Route::get("test", function () {
        return response([
            "message" => "Api Up and Running!",
            "success" => true
        ], 200);
    });


    // unprotected route
    Route::prefix("auth")->controller(AuthController::class)->group(function () {
        Route::post("register", "register");
        Route::post("login", "login");
    });

    // protected route
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::prefix("users")->controller(UserController::class)->group(function () {
            Route::get("/", "get");
        });

        Route::prefix("activities")->controller(ActivityController::class)->group(function () {
            Route::post("/", "store");
            Route::get("/", "get");
            Route::get("/{activity_id}", "getById");    
            Route::patch("mark-as-complete/{activity_id}", "markAsComplete");
            Route::delete("/{activity_id}", "delete");
        });
    });
});
