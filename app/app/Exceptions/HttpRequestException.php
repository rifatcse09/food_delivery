<?php

namespace App\Exceptions;

use Exception;

class HttpRequestException extends Exception
{
    protected $message = "Failed to handle the request.";
}
