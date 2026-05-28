<?php

namespace App\Exception;

class InvalidMoveException extends ChessException {
    public function __construct(string $message = "Ce coup n'est pas valide") {
        parent::__construct($message);
    }
}