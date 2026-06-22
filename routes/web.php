<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response([
        "message" => "Api Up and Running Successfully Now!",
        "success" => true
    ], 200);
});
