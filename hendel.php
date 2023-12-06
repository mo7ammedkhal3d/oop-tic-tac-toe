<?php
require_once 'gameState.php';
require_once 'gameMove.php';
require_once 'gameResult.php';
require_once 'player.php';
require_once 'game.php';

session_start(); // Start the session

define('ROOT_PATH', '/PHP/oop-tic-tac-toe/hendel.php');
$requst = str_replace(ROOT_PATH, '', $_SERVER['REQUEST_URI']);

// Retrieve or create the game object from the session
if (!isset($_SESSION['game'])) {
    $_SESSION['game'] = new Game();
}
$game = $_SESSION['game'];

if ($requst == '/start') {
    try {
        $player1 = new Player();
        $player1->userName = $_POST['player1name'];
        $player1->playerSymbol = $_POST['symbol'];
        $player2 = new Player();
        $player2->userName = $_POST['player2name'];
        if ($player1->playerSymbol == 'X') {
            $player2->playerSymbol = 'O';
        } else {
            $player2->playerSymbol = 'X';
        }
        $game->addPlayer($player1);
        $game->addPlayer($player2);

    } catch (\Throwable $th) {
        echo '<h1 style="background-color:red">' . $th->getMessage(). '</h1>';
    }
}

if ($requst == '/move') {
    $move = new GameMove();
    $move->x = $_POST['x'];
    $move->y = $_POST['y'];
    try {
        $game->makeMove($move);
    } catch (\Throwable $th){
        echo '<h1 style="background-color:red">' . $th->getMessage() . '</h1>';
    }
}

// Save the updated game object back to the session
$_SESSION['game'] = $game;
?>
