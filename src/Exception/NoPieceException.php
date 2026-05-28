<?php

namespace App\Exception;

class NoPieceException extends ChessException {
    public function __construct(string $message = "Aucune pièce ne se trouve à cette position") {
        parent::__construct($message);
    }
}