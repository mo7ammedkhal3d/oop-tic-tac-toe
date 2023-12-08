<?php

    require_once "Player.php";
    
    class GameInfo{
        public player $player1;
        public player $player2;

        public $result;

        public $moveStack=[];

        public player $Winner;

        public $startTime;
        public $finshTime;

    }
?>