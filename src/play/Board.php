<?php
//Kenneth Ward


//Records and manipulates board positions of both players
class Board{
    public $width;
    public $height;
    public $places;
    
    function __construct($width, $height){
        $this->width = $width;
        $this->height = $height;
        $column = array_fill(0, $height, 0);
        $this->places = array_fill(0, $width, $column);
    }
    
    //Places a token in the appropriate place.
    //Returns the row or -1 if it's full.
    function place($column, $player){
        for($row = $this->height-1; $row >= 0; $row--){
            if($this->places[$column][$row] == 0){
                $this->places[$column][$row] = $player;
                return $row;
            }
        }
        return -1;
    }
    
    //Recreates a Board object from a json string
    static function fromJson($json){
        $obj = json_decode($json);
        $width = $obj['width'];
        $height = $obj['height'];
        $places = $obj;
    }
}

?>