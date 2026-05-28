<?php

namespace App\Factory;

use App\Enum\PieceType;
use App\Enum\PieceColor;
use App\Position;
use App\Piece\King;
use App\Piece\Queen;
use App\Piece\Rook;
use App\Piece\Bishop;
use App\Piece\Knight;
use App\Piece\Pawn;
use App\Piece\Piece;

class PieceFactory {
    public function create(PieceType $type, PieceColor $color, Position $position): Piece {
        return match($type) {
            PieceType::KING => new King($color, $position),
            PieceType::QUEEN => new Queen($color, $position),
            PieceType::ROOK => new Rook($color, $position),
            PieceType::BISHOP => new Bishop($color, $position),
            PieceType::KNIGHT => new Knight($color, $position),
            PieceType::PAWN => new Pawn($color, $position),
        };
    }
}