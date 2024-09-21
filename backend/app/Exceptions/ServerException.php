<?php

namespace App\Exceptions;

use Exception;

class ServerException extends Exception
{
    public function __construct($message = "Erro interno no servidor", $code = 500)
    {
        parent::__construct($message, $code);
    }

    public function render($request = null)
    {
        return response()->json([
            'error' => $this->getMessage(),
            'type' => 'error'
        ], $this->getCode());
    }
}
