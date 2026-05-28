<?php

namespace App\Piece;

use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Position;
use App\Board;

class Pawn extends Piece {
    public function __construct(PieceColor $color, Position $position) {
        parent::__construct($color, $position);
        $this->type = PieceType::PAWN;
    }

    protected function isValidMovementShape(Position $target): bool {
        $rowDiff = $target->getRow() - $this->position->getRow();
        $colDiff = abs($target->getColumn() - $this->position->getColumn());
        $color = $this->getColor();

        if ($color === PieceColor::BLACK) { 

            if ($this->position->getRow() === 1) {
                if (($rowDiff >= 0 && $rowDiff <= 2) && ($colDiff === 0 || $colDiff === 1)) {
                    return true;
                }
            } else {
                if (($rowDiff >= 0 && $rowDiff <= 1) && ($colDiff === 0 || $colDiff === 1)) {
                    return true;
                }
            }
        } else if ($color === PieceColor::WHITE) {
            if ($this->position->getRow() === 6) {
                if (($rowDiff >= -2 && $rowDiff <= 0) && ($colDiff === 0 || $colDiff === 1)) {
                    return true;
                }
            } else {
                if (($rowDiff >= -1 && $rowDiff <= 0) && ($colDiff === 0 || $colDiff === 1)) {
                    return true;
                }
            }
        }
        return false;
    }

    protected function canCapture(Board $board, Position $target): bool {
        $pieceAtTarget = $board->getPieceAt($target);
        $colDiff = abs($target->getColumn() - $this->position->getColumn());

        if ($colDiff === 0) {
            return $pieceAtTarget === null;
        }
        
        if ($colDiff === 1) {
            return $pieceAtTarget !== null && $pieceAtTarget->getColor() !== $this->getColor();
        }

        return true;
    }

    public function render(): string {
        $color = $this->getColor();
        if ($color === PieceColor::BLACK) {
            return 'p';
        } else {
            return 'P';
        }
    }
}