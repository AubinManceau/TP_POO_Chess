<?php

namespace App\Exception;

class PromotionRequiredException extends ChessException {
    public function __construct() {
        parent::__construct("Un pion est arrivé à la promotion ! Choisissez une pièce.");
    }
}
