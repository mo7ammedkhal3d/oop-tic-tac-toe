<?php
require_once 'gameState.php';
require_once 'gameMove.php';
require_once 'gameResult.php';
require_once 'player.php';
require_once 'gameInfo.php';

class Game {

    public $players = [];
    public GameInfo $gameInfo;
    public $result;

    public $gameState;
    public $moveNo = 0;
    public player $currentPlayer;
    private array $board;

    public $onplying;

    public function __construct() {
        $this->board = [
            ["", "", ""],
            ["", "", ""],
            ["", "", ""]
        ];
        $gameState = GameState::AweaintngPlayer;
    }

    public function ShowBoard() {
        $boardArray = [];
        foreach ($this->board as $row) {
            $boardRow = [];
            foreach ($row as $column) {
                $boardRow[] = $column;
            }
            $boardArray[] = $boardRow;
        }
    
        return $boardArray;
    }

    public function check() {
        $result="";
        for($i = 0; $i < 3; $i++){
            if($this->board[$i][0] != "" && $this->board[$i][1] != "" && $this->board[$i][2] != "" &&
                $this->board[$i][0] == $this->board[$i][1] && $this->board[$i][1] == $this->board[$i][2]
                || $this->board[0][$i] != "" && $this->board[1][$i] != "" && $this->board[2][$i] != "" &&
                $this->board[0][$i] == $this->board[1][$i] && $this->board[1][$i] == $this->board[2][$i]) {

                $this->result = GameResult::WIN;
                $this->gameState = GameState::Finished;
                unset($_SESSION);
                session_destroy();
                $result ="Player : ".$this->currentPlayer->userName." Is ".GameResult::getResultString($this->result);
            }
        }

        if($this->board[0][0] != "" && $this->board[1][1] != "" && $this->board[2][2] != "" &&
            $this->board[0][0] == $this->board[1][1] && $this->board[1][1] == $this->board[2][2]
            || $this->board[0][2] != "" && $this->board[1][1] != "" && $this->board[2][0] != "" &&
            $this->board[0][2] == $this->board[1][1] && $this->board[1][1] == $this->board[2][0]) {

            $this->result = GameResult::WIN;
            // $this->gameInfo->result = GameResult::WIN;
            // $this->gameInfo->player1 = $this->players[0];
            // $this->gameInfo->player2 = $this->players[1];
            // $this->gameInfo->Winner=$this->currentPlayer;
            // $this->gameInfo->finshTime = date("H:i:s");
            $this->gameState = GameState::Finished;
            unset($_SESSION);
            session_destroy();
            $result ="Player : ".$this->currentPlayer->userName." Is ".GameResult::getResultString($this->result);
        }

        if($this->moveNo == 9) {
            // $this->gameInfo->result=GameResult::DRAW;
            // file_put_contents('GammingHistory.txt',$this->gameInfo);
            $this->result = GameResult::DRAW;
            $this->gameState = GameState::Finished;
            unset($_SESSION);
            session_destroy();
            $result ="Game Over : ".GameResult::getResultString($this->result);
        }
        return $result;
    }

    public function makeMove(GameMove $move){
        $moveDetils=[];
        if($this->board[$move->x][$move->y] == "") {
            $this->board[$move->x][$move->y] = $this->currentPlayer->playerSymbol;
            // $this->gameInfo->moveStack[] = $move;   
            $this->moveNo++;
            $moveDetils['board'] = $this->ShowBoard();
            $moveDetils['result']=$this->check();
            $this->Playerswith();
            return $moveDetils;
        } else
            throw new Exception("Select another cell");
    }

    function Playerswith() {
        if($this->currentPlayer == $this->players[0]) {
            $this->currentPlayer = $this->players[1];
        } else
            $this->currentPlayer = $this->players[0];
    }

    public function addPlayer(player $player) {
        if (!$this->gameState == GameState::InPlay) {
            if (!empty($this->players) && $this->players[0]->userName == $player->userName) {
                throw new Exception("You can't play yourself 😒");
            }
            if (count($this->players) < 2) {
                $this->players[] = $player;
            }
            if (count($this->players) == 2) {
                $playStart = "Play start <br>";
                $this->currentPlayer = $this->players[0];
                $this->gameState = GameState::InPlay;
    
                foreach ($this->players as $row) {
                    $playStart .= "Player: $row->userName";
                    if ($this->players[0] == $row) {
                        $playStart .= "&nbsp;&nbsp;&nbsp;VS&nbsp;&nbsp;&nbsp;";
                    }
                }
    
                return $playStart;
            }
        } else
            throw new Exception("The game already have two players in it");
    }

}