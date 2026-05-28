<?php

namespace App\Exception;

class WrongTurnException extends ChessException {
    public function __construct(string $message = "Ce n'est pas à votre tour de jouer") {
        parent::__construct($message);
    }
}