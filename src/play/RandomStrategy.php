<?php
//Kenneth Ward

require_once '../common/constants.php';

//Generates random computer moves
class RandomStrategy extends MoveStrategy{
    
    function __construct(Board $board = null) {
        $this->board = $board;
    }
    
    function pickSlot(){
        return rand(0,WIDTH-1);
    }
    
}

?>