<?php

require __DIR__ . '/vendor/autoload.php';

use App\Game;
use App\Move;
use App\Position;
use App\Exception\ChessException;

$game = new Game();

$game->start();

echo "\n";
echo "╔════════════════════════════╗\n";
echo "║   JEU D'ÉCHECS EN PHP      ║\n";
echo "╚════════════════════════════╝\n\n";

echo "=== PLATEAU INITIAL ===\n";
echo $game->getBoard()->render();
echo "\n";

while (true) {
    $currentPlayer = $game->getCurrentPlayer();
    $playerName = ($currentPlayer->name === 'WHITE') ? 'Blanc' : 'Noir';
    
    echo "───────────────────────────\n";
    echo "À qui de jouer : $playerName\n";
    echo "───────────────────────────\n";
    echo "Entrez un coup (format: 'from to')\n";
    echo "Exemple: '6:4 4:4' (pion blanc e2 vers e4)\n";
    echo "Tapez 'quit' pour quitter\n";
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
        echo "\n=== PLATEAU ===\n";
        echo $game->getBoard()->render();
        echo "\n";
        
    } catch (ChessException $e) {
        echo "\n❌ Erreur : " . $e->getMessage() . "\n\n";
    } catch (Exception $e) {
        echo "\n❌ Erreur : " . $e->getMessage() . "\n\n";
    }
}