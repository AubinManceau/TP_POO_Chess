<?php

namespace App\Enum;

enum PieceColor {
    case WHITE;
    case BLACK;

    public function opposite(): PieceColor {
        return match($this) {
            self::WHITE => self::BLACK,
            self::BLACK => self::WHITE,
        };
    }
}