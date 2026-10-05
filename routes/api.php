<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\ActivityController;
use App\Http\Controllers\Api\v1\ActivityPointController;
use App\Http\Controllers\Api\v1\ActivityPauseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix("v1")->group(function () {

    // testing api endpoint
    Route::get("test", function () {
        return response([
            "message" => "Api Up and Running Successfully!",
            "success" => true
        ], 200);
    });


    // unprotected route
    Route::prefix("auth")->controller(AuthController::class)->group(function () {
        Route::post("register", "register");
        Route::post("login", "login");
        Route::post("login/google", "googleLogin");
        Route::post("check-email", "checkEmail");
        Route::post("forgot-password","forgotPassword");
        Route::post("reset-password","resetPassword");
    });

    

    

    // protected route
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::prefix("users")->controller(UserController::class)->group(function () {
            Route::get("/", "get");
            Route::post("/","update");
        });

        Route::prefix("activities")->controller(ActivityController::class)->group(function () {
            Route::post("/", "store");
            Route::get("/", "get");
            Route::get("/{activity_id}", "getById");
            Route::patch("stop-activity/{activity_id}", "markAsComplete");
            Route::delete("/{activity_id}", "delete");
            Route::get("check-if-active/{activity_id}","getActiveActivityById");
        });

        Route::prefix("activity-points")->controller(ActivityPointController::class)->group(function () {
            Route::post("/", "store");
        });

        Route::prefix("activity-pauses")->controller(ActivityPauseController::class)->group(function(){
            Route::post("/","store");
        });
    });
});
