<?php
//Kenneth Ward

require_once '../common/constants.php';

class SmartStrategy extends MoveStrategy{
    
    function __construct(Board $board = null) {
        $this->board = $board;
    }
    
    function pickSlot(){
        $pick = $this->checkForNearWin(1);
        if($pick > -1)return $pick;
        $pick = $this->checkForNearWin(2);
        if($pick > -1)return $pick;
        
        return $this->randomMove();
    }
    
    function checkForNearWin($player){
        //Visits every position
        for($column = 0; $column < WIDTH; $column++){
            for($row = 0; $row < HEIGHT; $row++){
                
                //If chip at current position belongs to player of interest, search every direction
                if($this->board->places[$column][$row] == $player){
                    
                    //Initialize counter and array for winning row coordinates.
                    //These values will get reset after each direction
                    $count = 0;
                    
                    //Search to the right
                    for($right = 0; $right <= 3 and ($column + $right) < WIDTH; $right++){
                        //If player of interest's chip, increment counter
                        if($this->board->places[$column + $right][$row] == $player){
                            $count++;
                            if($count == 3){
                                if($column+4 < WIDTH){
                                    if($row == HEIGHT-1 or $this->board->places[$column+4][$row+1] == 0){
                                        return $column+4;
                                    }
                                }
                                if($column-1 >= 0){
                                    if($row == HEIGHT-1 or $this->board->places[$column-1][$row+1] == 0){
                                        return $column-1;
                                    }
                                }
                                
                            }
                        }
                        else{break;}
                    }
                    
                    $count = 0;
                    //Search downward
                    for($down = 0; $down <= 3 and ($row + $down) < HEIGHT; $down++){
                        if($this->board->places[$column][$row + $down] == $player){
                            $count++;
                            if($count == 3 and $row-1 > 0){
                                if($this->board->places[$column][$row-1] == 0){
                                    return $column;
                                }
                            }
                        }
                        else{break;}
                    }
                    
                    $count = 0;
                    //Search right downward diagonal
                    for($diagonal = 0; $diagonal <= 3 and ($diagonal + $row) <HEIGHT and ($diagonal + $column) < WIDTH; $diagonal++){
                        if($this->board->places[$diagonal + $column][$diagonal + $row] == $player){
                            $count++;
                            if($count == 3){
                                if($column-1 > 0 and $row-1 > 0){
                                    if($this->board->places[$column-1][$row-1] == 0 and $this->board->places[$column-1][$row] != 0){
                                        return $column-1;
                                    }
                                }
                            }
                            
                        }
                        else{break;}
                    }
                    
                    $count = 0;
                    //Search right upward diagonal
                    for($diagonal = 0; $diagonal <= 3 and ($row - $diagonal) >= 0 and ($diagonal + $column) < WIDTH; $diagonal++){
                        if($this->board->places[$diagonal + $column][$row - $diagonal] == $player){
                            $count++;
                            if($count == 3 and $row-3 > 0 and $column+3 < WIDTH){
                                if($this->board->places[$column+3][$row-3] == 0 and $this->board->places[$column+3][$row-2] != 0){
                                    return $column+3;
                                }
                            
                            }
                        }
                        else{break;}
                    }
                    
                    $count = 0;
                    //Search left downward diagonal
                    for($diagonal = 0; $diagonal <= 3 and ($diagonal + $row) <HEIGHT and ($column - $diagonal) >= 0; $diagonal++){
                        if($this->board->places[$column - $diagonal][$diagonal + $row] == $player){
                            $count++;
                            if($count == 3 and $row-1 > 0 and $column+4 < WIDTH){
                                if($this->board->places[$column+4][$row-1] == 0 and $this->board->places[$column+4][$row-2] != 0){
                                    return $column+4;
                                }
                            }
                        }
                        else{break;}
                    }
                }
                //NOTE: Up, left, and left upward diagonal are redundant to down, right, and downward right, and are "ignored"
            }
        }
        return -1;
    }
    
    function randomMove(){
        return rand(0,WIDTH-1);
    }
}

?>
