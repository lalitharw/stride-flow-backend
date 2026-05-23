<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response([
        "message" => "Api Up and Running!",
        "success" => true
    ], 200);
});
