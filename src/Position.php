<?php

namespace App;

class Position {
    private int $row;
    private int $column;

    public function __construct(int $row, int $column) {
        if ($row < 0 || $row > 7) {
            return;
        }

        if ($column < 0 || $column > 7) {
            return;
        }

        $this->row = $row;
        $this->column = $column;
    }

    public function getRow(): int {
        return $this->row;
    }

    public function getColumn(): int {
        return $this->column;
    }

    public function equals(Position $other): bool {
        if ($this->row === $other->row && $this->column === $other->column) {
            return true;
        } else {
            return false;
        }
    }

    public function toKey(): string {
        $strRow = strval($this->getRow());
        $strColumn = strval($this->getColumn());

        $key = $strRow . ':' . $strColumn;
        return $key;
    }

    public static function fromKey(string $key): Position {
        $position = explode(':', $key);

        $row = $position[0];
        $column = $position[1];

        return new Position($row, $column);
    }
} 