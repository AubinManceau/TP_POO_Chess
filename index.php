<?php

require __DIR__ . '/vendor/autoload.php';

use App\Game;
use App\Move;
use App\Position;
use App\Exception\ChessException;
use App\Exception\PromotionRequiredException;
use App\Enum\PieceType;

$game = new Game();

$game->start();

echo $game->getBoard()->render();
echo "\n";

while (true) {
    $currentPlayer = $game->getCurrentPlayer();
    $playerName = ($currentPlayer->name === 'WHITE') ? 'Blanc' : 'Noir';
    
    echo "───────────────────────────\n";
    echo "À qui de jouer : $playerName\n";
    echo "───────────────────────────\n";
    echo "Entrez un coup (format: 'fromRow:fromCol toRow:toCol')\n";
    echo "Exemple: '6:4 4:4'\n";
    echo "Tapez 'exit' pour quitter\n";
    echo "> ";
    
    $input = trim(fgets(STDIN));
    
    if (strtolower($input) === 'exit') {
        echo "\nPartie terminée.\n";
        break;
    }
    
    try {
        $move = null;
        
        $positions = explode(' ', $input);
        if (count($positions) !== 2) {
            throw new Exception("Format invalide. Utilisez: 'from:to'");
        }
        $move = new Move(
            Position::fromKey($positions[0]),
            Position::fromKey($positions[1])
        );
        
        $game->play($move);
        
        echo "\n✓ Coup joué avec succès !\n";
        echo $game->getBoard()->render();
        echo "\n";
        
    } catch (PromotionRequiredException $e) {
        echo "\n" . $e->getMessage() . "\n";
        echo "\nChoisissez la pièce de promotion :\n";
        echo "  1 - Dame (Queen)\n";
        echo "  2 - Tour (Rook)\n";
        echo "  3 - Fou (Bishop)\n";
        echo "  4 - Cavalier (Knight)\n";
        echo "> ";
        
        $choice = trim(fgets(STDIN));
        
        $promotionType = match ($choice) {
            '1' => PieceType::QUEEN,
            '2' => PieceType::ROOK,
            '3' => PieceType::BISHOP,
            '4' => PieceType::KNIGHT,
            default => null
        };
        
        if ($promotionType === null) {
            echo "\n❌ Choix invalide. Promotion avec Dame par défaut.\n\n";
            $promotionType = PieceType::QUEEN;
        }
        
        try {
            $game->promotePiece($move->getTo(), $promotionType);
            echo "\n✓ Pion promu en " . $promotionType->name . " !\n";
            echo $game->getBoard()->render();
            echo "\n";
        } catch (ChessException $promotionError) {
            echo "\n❌ Erreur de promotion : " . $promotionError->getMessage() . "\n\n";
        }
    } catch (ChessException $e) {
        echo "\n❌ Erreur : " . $e->getMessage() . "\n\n";
    } catch (Exception $e) {
        echo "\n❌ Erreur : " . $e->getMessage() . "\n\n";
    }
}