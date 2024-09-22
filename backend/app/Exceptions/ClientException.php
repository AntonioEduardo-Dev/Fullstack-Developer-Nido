<?php

namespace App\Exceptions;

use Exception;

class ClientException extends Exception
{
    protected $messageClient;
    
    //
    public function __construct($message = "Solicitação invalida", $code = 400, $messageClient = null)
    {
        parent::__construct($message, 400);
        $this->messageClient = $messageClient ?? $message;
    }

    public function render($request = null)
    {
        return response()->json([
            'error' => $this->getMessage(),
            'type' => 'error',
            'message' => $this->messageClient
        ], $this->getCode());
    }
}