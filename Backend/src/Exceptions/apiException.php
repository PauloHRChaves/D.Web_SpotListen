<?php
namespace src\Exceptions;

use Exception;

class ApiException extends Exception {
    public function __construct($message, $httpCode = 500) {
        parent::__construct($message, $httpCode);
    }
}