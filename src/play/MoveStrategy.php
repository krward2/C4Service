<?php
//Kenneth Ward

//Abstarct class, defines some variables and methods for SmartStrategy and RandomStrategy
abstract class MoveStrategy {
    var $board;
    
    function __construct(Board $board = null) {
        $this->board = $board;
    }
    
    abstract function pickSlot();
    
    function toJson() {
        return array(‘name’ => get_class($this));
    }
    
    static function fromJson() {
        $strategy = new static();
        return $strategy;
    }
}

?>
