<?php

require __DIR__ . '/vendor/autoload.php';

use App\Game;
use App\Move;
use App\Position;
use App\Exception\ChessException;

// Créer une instance de Game
$game = new Game();

// Démarrer la partie
$game->start();

// Afficher le plateau initial
echo "\n";
echo "╔════════════════════════════╗\n";
echo "║   JEU D'ÉCHECS EN PHP      ║\n";
echo "╚════════════════════════════╝\n\n";

echo "=== PLATEAU INITIAL ===\n";
echo $game->getBoard()->render();
echo "\n";

// Boucle de jeu
while (true) {
    $currentPlayer = $game->getCurrentPlayer();
    $playerName = ($currentPlayer->name === 'WHITE') ? 'Blanc' : 'Noir';
    
    echo "───────────────────────────\n";
    echo "À qui de jouer : $playerName\n";
    echo "───────────────────────────\n";
    echo "Entrez un coup (format: 'from_row from_col to_row to_col')\n";
    echo "Exemple: '6 4 5 4' (pion blanc c2 vers c3)\n";
    echo "Tapez 'quit' pour quitter\n";
    echo "> ";
    
    $input = trim(fgets(STDIN));
    
    // Vérifier si l'utilisateur veut quitter
    if (strtolower($input) === 'quit') {
        echo "\nParti terminée. Au revoir !\n";
        break;
    }
    
    // Parser l'entrée
    $parts = explode(' ', $input);
    if (count($parts) !== 4) {
        echo "❌ Format invalide. Utilisez: 'from_row from_col to_row to_col'\n\n";
        continue;
    }
    
    $fromRow = (int)$parts[0];
    $fromCol = (int)$parts[1];
    $toRow = (int)$parts[2];
    $toCol = (int)$parts[3];
    
    try {
        // Jouer le coup
        $move = new Move(new Position($fromRow, $fromCol), new Position($toRow, $toCol));
        $game->play($move);
        
        echo "\n✓ Coup joué avec succès !\n";
        echo "\n=== PLATEAU ===\n";
        echo $game->getBoard()->render();
        echo "\n";
        
    } catch (ChessException $e) {
        echo "\n❌ Erreur : " . $e->getMessage() . "\n\n";
    } catch (Exception $e) {
        echo "\n❌ Erreur inattendue : " . $e->getMessage() . "\n\n";
    }
}