<?php

namespace App\Piece;

use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Position;

class Queen extends Piece {
    public function __construct(PieceColor $color, Position $position) {
        parent::__construct($color, $position);
        $this->type = PieceType::QUEEN;
    }

    protected function isValidMovementShape(Position $target): bool {
        $absRow = abs($target->getRow() - $this->position->getRow());
        $absCol = abs($target->getColumn() - $this->position->getColumn());

        if (($absRow === 0 || $absCol === 0) || $absRow === $absCol) {
            return true;
        }

        return false;
    }

    public function render(): string {
        $color = $this->getColor();
        if ($color === PieceColor::BLACK) {
            return 'q';
        } else {
            return 'Q';
        }
    }
}