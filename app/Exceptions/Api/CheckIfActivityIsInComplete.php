<?php

namespace App\Exceptions\Api;

use App\Trait\Api\ResponseTrait;
use Exception;

class CheckIfActivityIsInComplete extends Exception
{
    use ResponseTrait;
    public function render()
    {
        return $this->errorResponse(message: "You already have started a new activity which is not yet completed!");
    }
}
