<?php

namespace App\Trait\Api;

trait ResponseTrait
{
    protected function successResponse($data = [], $success = true, $message = "", $status_code = 200)
    {
        return response([
            "data" => $data,
            "message" => $message,
            "success" => $success
        ], $status_code);
    }

    protected function errorResponse($success = false, $message = "", $status_code = 400)
    {
        return response([
            "message" => $message,
            "success" => $success
        ], $status_code);
    }
}
