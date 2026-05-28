<?php

namespace App;
use App\Board;
use App\Enum\PieceColor;
use App\Enum\PieceType;
use App\Exception\InvalidMoveException;
use App\Exception\NoPieceException;
use App\Exception\WrongTurnException;
use App\Exception\OccupiedByAllyException;
use App\Factory\PieceFactory;
use App\Move;
use App\Position;


class Game {
    private Board $board;
    private PieceColor $currentPlayer;
    private PieceFactory $pieceFactory;

    public function __construct() {
        $this->board = new Board();
        $this->currentPlayer = PieceColor::WHITE;
        $this->pieceFactory = new PieceFactory();
    }

    public function start(): void {
        $this->setupPieces(); 
    }

    public function getBoard(): Board {
        return $this->board;
    }

    public function getCurrentPlayer(): PieceColor {
        return $this->currentPlayer;
    }

    public function play(Move $move): void {
        $piece = $this->board->getPieceAt($move->getFrom());
        if ($piece === null) {
            throw new NoPieceException();
        }

        if ($piece->getColor() !== $this->currentPlayer) {
            throw new WrongTurnException();
        }

        if ($piece->isOccupiedByAlly($this->board, $move->getTo())) {
            throw new OccupiedByAllyException();
        }

        if (!$piece->canMove($this->board, $move->getTo())) {
            throw new InvalidMoveException();
        }

        $this->board->movePiece($move->getFrom(), $move->getTo());

        $this->isCheck($this->currentPlayer);
        
        $this->switchPlayer();
    }

    public function isCheck(PieceColor $color): bool {
        $opponentKingPos = $this->board->getKingPosition($color->opposite());
        if ($opponentKingPos === null) {
            return false;
        }

        $allPieces = $this->board->getPieces();

        foreach ($allPieces as $piece) {
            if ($piece->getColor() !== $color) {
                continue;
            }

            if ($piece->canMove($this->board, $opponentKingPos)) {
                return true;
            }
        }

        return false;
    }

    private function setupPieces(): void {
        $this->board->placePiece($this->pieceFactory->create(PieceType::ROOK, PieceColor::BLACK, new Position(0, 0)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KNIGHT, PieceColor::BLACK, new Position(0, 1)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::BISHOP, PieceColor::BLACK, new Position(0, 2)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::QUEEN, PieceColor::BLACK, new Position(0, 3)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KING, PieceColor::BLACK, new Position(0, 4)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::BISHOP, PieceColor::BLACK, new Position(0, 5)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KNIGHT, PieceColor::BLACK, new Position(0, 6)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::ROOK, PieceColor::BLACK, new Position(0, 7)));

        for ($col = 0; $col < 8; $col++) {
            $this->board->placePiece($this->pieceFactory->create(PieceType::PAWN, PieceColor::BLACK, new Position(1, $col)));
        }

        $this->board->placePiece($this->pieceFactory->create(PieceType::ROOK, PieceColor::WHITE, new Position(7, 0)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KNIGHT, PieceColor::WHITE, new Position(7, 1)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::BISHOP, PieceColor::WHITE, new Position(7, 2)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::QUEEN, PieceColor::WHITE, new Position(7, 3)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KING, PieceColor::WHITE, new Position(7, 4)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::BISHOP, PieceColor::WHITE, new Position(7, 5)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::KNIGHT, PieceColor::WHITE, new Position(7, 6)));
        $this->board->placePiece($this->pieceFactory->create(PieceType::ROOK, PieceColor::WHITE, new Position(7, 7)));

        for ($col = 0; $col < 8; $col++) {
            $this->board->placePiece($this->pieceFactory->create(PieceType::PAWN, PieceColor::WHITE, new Position(6, $col)));
        }

    }

    private function switchPlayer(): void {
        $this->currentPlayer = $this->currentPlayer->opposite();
    }
}