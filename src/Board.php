<?php

namespace App;

use App\Contract\Renderable;
use App\Piece\Piece;
use App\Enum\PieceColor;
use App\Enum\PieceType;

class Board implements Renderable {
    private array $pieces = [];

    public function placePiece(Piece $piece): void {
        $key = $piece->getPosition()->toKey();
        $this->pieces[$key] = $piece;
    }

    public function getPieceAt(Position $position): ?Piece {
        $key = $position->toKey();
        return $this->pieces[$key] ?? null;
    }

    public function hasPieceAt(Position $position): bool {
        $key = $position->toKey();
        return array_key_exists($key, $this->pieces);
    }

    public function removePieceAt(Position $position): void {
        $key = $position->toKey();
        if ($this->hasPieceAt($position)) {
            unset($this->pieces[$key]);
        }
    }

    public function movePiece(Position $from, Position $to): void {
        $fromKey = $from->toKey();
        $toKey = $to->toKey();

        $piece = $this->pieces[$fromKey];
        if ($piece) {
            $piece->setPosition($to);
            $this->pieces[$toKey] = $piece;
            unset($this->pieces[$fromKey]);
        }
    }

    public function isPathClear(Position $from, Position $to): bool {
        $rowDiff = $to->getRow() - $from->getRow();
        $colDiff = $to->getColumn() - $from->getColumn();

        $rowStep = ($rowDiff === 0) ? 0 : ($rowDiff > 0 ? 1 : -1);
        $colStep = ($colDiff === 0) ? 0 : ($colDiff > 0 ? 1 : -1);
    
        $currentRow = $from->getRow() + $rowStep;
        $currentCol = $from->getColumn() + $colStep;

        while ($currentRow !== $to->getRow() || $currentCol !== $to->getColumn()) {
            $position = new Position($currentRow, $currentCol);
            if ($this->hasPieceAt($position)) {
                return false;
            }
            $currentRow += $rowStep;
            $currentCol += $colStep;
        }

        return true;
    }

    public function getPieces(): array {
        return $this->pieces;
    }

    public function getKingPosition(PieceColor $color): ?Position {
        foreach ($this->pieces as $piece) {
            if ($piece->getType() === PieceType::KING && $piece->getColor() === $color) {
                return $piece->getPosition();
            }
        }
        return null;
    }

    public function render(): string {
        $output = "";
        
        $output .= "    ";
        for ($col = 0; $col < 8; $col++) {
            $output .= $col . "   ";
        }
        $output .= "\n";
        
        $output .= "  ┌───┬───┬───┬───┬───┬───┬───┬───┐\n";
        
        for ($row = 0; $row < 8; $row++) {
            $output .= $row . " │";
            for ($col = 0; $col < 8; $col++) {
                $pos = new Position($row, $col);
                $piece = $this->getPieceAt($pos);
                $cellContent = ($piece !== null) ? $piece->render() : " ";
                $output .= " " . $cellContent . " │";
            }
            $output .= "\n";
            
            if ($row < 7) {
                $output .= "  ├───┼───┼───┼───┼───┼───┼───┼───┤\n";
            }
        }
        
        $output .= "  └───┴───┴───┴───┴───┴───┴───┴───┘\n";
        
        return $output;
    }
}