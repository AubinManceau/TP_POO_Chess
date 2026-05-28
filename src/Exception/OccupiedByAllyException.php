<?php

namespace App\Exception;

class OccupiedByAllyException extends ChessException {
    public function __construct(string $message = "Cette case est occupée par une pièce alliée") {
        parent::__construct($message);
    }
}