<?php
class GameResult{
    public const  WIN = 1;
    public const  DRAW = 2;
    public const  LOSE = 3;

    public static function getResultString($result) {
        switch ($result) {
            case self::WIN:
                return 'Win';
            case self::DRAW:
                return 'Draw';
            case self::LOSE:
                return 'Lose';
            default:
                return 'Unknown Result';
        }
    }
}