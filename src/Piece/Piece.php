<?php

namespace App\Piece;

use App\Contract\Renderable;
use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Position;
use App\Board;

abstract class Piece implements Renderable {
    protected PieceColor $color;
    protected Position $position;
    protected PieceType $type;

    public function __construct(PieceColor $color, Position $position) {
        $this->color = $color;
        $this->position = $position;
    }

    public function getColor(): PieceColor {
        return $this->color;
    }

    public function getPosition(): Position {
        return $this->position;
    }

    public function setPosition(Position $position): void {
        $this->position = $position;
    }

    public function getType(): PieceType {
        return $this->type;
    }

    public function canMove(Board $board, Position $target): bool {
        if ($target === $this->getPosition()) {
            return false;
        }

        if ($this->isValidMovementShape($target) === false) {
            return false;
        }

        if ($this->canCapture($board, $target) === false) {
            return false;
        }

        if ($this->getType() !== PieceType::KNIGHT) {
            return $board->isPathClear($this->getPosition(), $target);
        }

        return true;
    }

    protected function canCapture(Board $board, Position $target): bool {
        if ($this->isOccupiedByAlly($board, $target)) {
            return false;
        }

        return true;
    }

    public function isOccupiedByAlly(Board $board, Position $target): bool {
        $pieceAtTarget = $board->getPieceAt($target);
        return $pieceAtTarget !== null && $pieceAtTarget->getColor() === $this->getColor();
    }

    abstract protected function isValidMovementShape(Position $target): bool;

    abstract public function render(): string;
}