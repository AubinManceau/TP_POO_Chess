<?php

namespace App\Piece;

use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Position;

class Knight extends Piece {
    public function __construct(PieceColor $color, Position $position) {
        parent::__construct($color, $position);
        $this->type = PieceType::KNIGHT;
    }

    protected function isValidMovementShape(Position $target): bool {
        $absRow = abs($target->getRow() - $this->position->getRow());
        $absCol = abs($target->getColumn() - $this->position->getColumn());

        if (($absRow === 1 && $absCol === 2) || ($absRow === 2 && $absCol === 1)) {
            return true;
        }

        return false;
    }

    public function render(): string {
        $color = $this->getColor();
        if ($color === PieceColor::BLACK) {
            return 'n';
        } else {
            return 'N';
        }
    }
}