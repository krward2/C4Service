<?php
//Kenneth Ward

require_once '../common/constants.php';
require_once 'Board.php';
require_once 'MoveStrategy.php';
require_once 'SmartStrategy.php';
require_once 'RandomStrategy.php';
require_once 'Move.php';


class Game {
    public $strategy;
    public $board;
   
    function __construct($strategy = null){
        $this->board = new Board(WIDTH, HEIGHT);
        $this->strategy = $strategy;
    }
    
    //Creates a Move object for the player's move
    function makePlayerMove($slot){
        //Updates board
        $this->board->place($slot, 1);
        
        //Checks for player win and recieves coordinates of winning 
        //row or empty array if no win (1 = Human 2 = Computer)
        $row = $this->checkWin(1);
        
        //Returns the appropriate move
        if($row){
            //Winning move
            return new Move($slot, TRUE, FALSE, $row);
        }
        //checkDraw() returns boolean
        elseif ($this->checkDraw()){
            //Draw move
            return new Move($slot, FALSE, TRUE, []);
        }
        //Boring move
        return new Move($slot, FALSE, FALSE, []);
    }
    
    //Creates move object for computer's move
    function makeOpponentMove(){
        //Strategy generates a move
        $opponentMove = $this->strategy->pickSlot();
        
        //Ensures strategy generates valid move
        $isValid = $this->board->place($opponentMove, 2);     
        while($isValid < 0){
            $opponentMove = $this->strategy->pickSlot();
            $isValid = $this->board->place($opponentMove, 2);
        }
        
        //Checks for computer win
        $row = $this->checkWin(2);
        if($row){
            //Winning move
            return new Move($opponentMove, TRUE, FALSE, $row);
        }
        elseif ($this->checkDraw()){
            //Draw move
            return new Move($opponentMove, FALSE, TRUE, []);
        }
        //Boring move
        return new Move($opponentMove, FALSE, FALSE, []);
    }
    
    //Searches for winning position for given player (1 = Human 2 = Computer) 
    function checkWin($player){
        //Visits every position
        for($column = 0; $column < WIDTH; $column++){
            for($row = 0; $row < HEIGHT; $row++){
                
                //If chip at current position belongs to player of interest, search every direction
                if($this->board->places[$column][$row] == $player){
                    
                    //Initialize counter and array for winning row coordinates.
                    //These values will get reset after each direction
                    $count = 0;
                    $winningRow = [];
                    
                    //Search to the right
                    for($right = 0; $right <= 4 and ($column + $right) < WIDTH; $right++){
                        //If player of interest's chip, increment counter
                        if($this->board->places[$column + $right][$row] == $player){
                            $count++;
                            array_push($winningRow, ($column + $right), $row);
                            //If 4 in a row, return coorinates
                            if($count == 4)return $winningRow;
                        }
                        else{break;}
                    }
                    
                    $count = 0;
                    $winningRow = [];
                    //Search downward
                    for($down = 0; $down <= 4 and ($row + $down) < HEIGHT; $down++){
                        if($this->board->places[$column][$row + $down] == $player){
                            $count++;
                            array_push($winningRow, $column, ($row + $down));
                            if($count == 4)return $winningRow;
                        }
                        else{break;}
                    }
                    
                    $count = 0;
                    $winningRow = [];
                    //Search right downward diagonal
                    for($diagonal = 0; $diagonal <= 4 and ($diagonal + $row) <HEIGHT and ($diagonal + $column) < WIDTH; $diagonal++){
                        if($this->board->places[$diagonal + $column][$diagonal + $row] == $player){
                            $count++;
                            array_push($winningRow, ($diagonal + $column), ($diagonal + $row));
                            if($count == 4)return $winningRow;
                        }
                        else{break;}
                    }
                    
                    $count = 0;
                    $winningRow = [];
                    //Search right upward diagonal
                    for($diagonal = 0; $diagonal <= 4 and ($row - $diagonal) >= 0 and ($diagonal + $column) < WIDTH; $diagonal++){
                        if($this->board->places[$diagonal + $column][$row - $diagonal] == $player){
                            $count++;
                            array_push($winningRow, ($diagonal + $column), ($row - $diagonal));
                            if($count == 4)return $winningRow;
                        }
                        else{break;}
                    }
                    
                    $count = 0;
                    $winningRow = [];
                    //Search left downward diagonal
                    for($diagonal = 0; $diagonal <= 4 and ($diagonal + $row) <HEIGHT and ($column - $diagonal) >= 0; $diagonal++){
                        if($this->board->places[$column - $diagonal][$diagonal + $row] == $player){
                            $count++;
                            array_push($winningRow, ($column - $diagonal), ($diagonal + $row));
                            if($count == 4)return $winningRow;
                        }
                        else{break;}
                    }
                }
                //NOTE: Up, left, and left upward diagonal are redundant to down, right, and downward right, and are "ignored"
            }
        }
        return null;
    }
    
    //Only a draw if the board is filled and no winners
    function checkDraw(){
        for($column = 0; $column < WIDTH; $column++){
            for($row = 0; $row < HEIGHT; $row++ ){
                // empty space = 0
                if($this->board->places[$column][$row] == 0){return FALSE;}
            }
        }
        return TRUE;
    }
    
    //Recreates Game object from a json string
    static function fromJsonString($json) {
        $obj = json_decode($json);
        $name = $obj->{'strategy'}->name;
        $board = $obj->{'board'};
        $game = new Game();
        $game->board = new Board($board->width, $board->height);
        $game->board->places = $board->places;
        $game->strategy = $name::fromJson();
        $game->strategy->board = $game->board;
        return $game;
    }
    
    //Encodes game object to json string
    function toJsonString(){
        $this->strategy = array('name' => get_class($this->strategy));
        return json_encode($this);
    }
}

?>