<?php 
//Kenneth Ward

//Records basic move information
class Move{
    public $slot;
    public $isWin;
    public $isDraw;
    public $row;
    
    function __construct($slot, $isWin, $isDraw, $row){
        $this->slot = $slot;
        $this->isWin = $isWin;
        $this->isDraw = $isDraw;
        $this->row = $row;
    }
}
?>

