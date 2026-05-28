<?php

namespace App\Exception;
use Exception;

class ChessException extends Exception {
    public function __construct(string $message = "Une erreur est survenue au cours de la partie") {
        parent::__construct($message);
    }
}