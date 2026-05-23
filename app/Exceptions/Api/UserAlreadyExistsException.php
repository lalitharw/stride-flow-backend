<?php

namespace App\Exceptions\Api;

use Exception;

class UserAlreadyExistsException extends Exception
{
    public function __construct(protected string $error_message = 'User Already Exists', protected int $status_code = 400)
    {
        $this->error_message = $error_message;
        $this->status_code = $status_code;
    }
    public function render()
    {
        return response([
            "message" => $this->error_message,
            "success" => false
        ], $this->status_code);
    }
}
