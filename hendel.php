<?php
require_once 'tic-tac-toe/gameState.php';
require_once 'tic-tac-toe/gameMove.php';
require_once 'tic-tac-toe/gameResult.php';
require_once 'tic-tac-toe/player.php';
require_once 'tic-tac-toe/game.php';

session_start(); 

define('ROOT_PATH', '/PHP/oop-tic-tac-toe/hendel.php');
$requst = str_replace(ROOT_PATH, '', $_SERVER['REQUEST_URI']);


if(!isset($_SESSION['game'])) {
    $_SESSION['game'] = new Game();
}
$game = $_SESSION['game'];

if($requst == '/start') {
    try {
        $player1 = new Player();
        $player1->userName = $_POST['player1name'];
        $player1->playerSymbol = $_POST['symbol'];
        $player2 = new Player();
        $player2->userName = $_POST['player2name'];
        if($player1->playerSymbol == 'X') {
            $player2->playerSymbol = 'O';
        } else {
            $player2->playerSymbol = 'X';
        }
        $game->addPlayer($player1);
        $playStart=$game->addPlayer($player2);
        echo json_encode(['success' => true,'playStart'=> $playStart]);
    } catch (\Throwable $th) {
        echo json_encode(['success' => false, 'error' => $th->getMessage()]);
    }
}

if ($requst == '/move') {
    $move = new GameMove();
    $move->x = $_POST['x'];
    $move->y = $_POST['y'];
    try {
        $moveDetils = $game->makeMove($move);
        echo json_encode(['success' => true, 'moveDetils' => $moveDetils]);
    } catch (\Throwable $th) {
        echo json_encode(['success' => false, 'error' => $th->getMessage()]);
    }
}

$_SESSION['game'] = $game;
?>