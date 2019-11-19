<?php
//Kenneth Ward

require_once '../common/constants.php';
require_once '../common/utils.php';
require_once './Game.php';

//Error Checking
//Make sure pid is specified
if(!array_key_exists('pid', $_GET)){
    $error = array('response' => FALSE, 'reason' => "Pid not specified");
    echo json_encode($error);
    exit;
}

//Make sure a game exists for given pid
$pid = $_GET['pid'];
if(!file_exists(DATA_DIR . $pid . DATA_EXT)){
    $error = array('response' => FALSE, 'reason' => "Unknown pid");
    echo json_encode($error);
    exit;
}

//Make sure a move is given
if(!array_key_exists('move', $_GET)){
    $error = array('response' => FALSE, 'reason' => "Move not specified");
    echo json_encode($error);
    exit;
}

//Make sure move is on the board
$move = $_GET['move'];
if($move < 0 or $move > WIDTH){
    $error = array('response' => FALSE, 'reason' => "Invalid slot, $move");
    echo json_encode($error);
    exit;
}

//Retrive game state and create corresponding Game object
$file = DATA_DIR . $pid . DATA_EXT;
$json = file_get_contents($file);
$game = Game::fromJsonString($json);

//Encode player move and check for win
$playerMove = $game->makePlayerMove((int)$move);
if ($playerMove->isWin || $playerMove->isDraw) {
    unlink($file);
    //Send player move
    echo createResponse($playerMove);
    exit; 
}

//Generate computer move
$opponentMove = $game->makeOpponentMove();

//Check for computer win
if ($opponentMove->isWin || $opponentMove->isDraw) {
    unlink($file);
    //Send moves
    echo createResponse($playerMove, $opponentMove);
    exit; 
}

//Send moves
echo createResponse($playerMove, $opponentMove);
storeState($file, $game->toJsonString());

//Encodes player and computer moves to json
function createResponse($playerMove, $opponentMove = null) {
    $result = array("response" => true, "ack_move" => $playerMove);
    if ($opponentMove != null) { $result["move"] = $opponentMove; }
    return json_encode($result);
}
  
?>