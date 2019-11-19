<?php
//Kenneth Ward

require_once '../common/constants.php';

//Supported strategies
$strategies = array("Smart" => "SmartStrategy", "Random" => "RandomStrategy");

//Sends game info to client
$info = new GameInfo(WIDTH, HEIGHT, array_keys($strategies));
echo json_encode($info); 

class GameInfo {
   public $width;
   public $height;
   public $strategies;
   function __construct($width, $height, $strategies) {
      $this->width = $width;
      $this->height = $height;
      $this->strategies = $strategies;
      }
}
?>
