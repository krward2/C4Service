<?php
//Kenneth Ward

require_once '../common/constants.php';
require_once '../common/utils.php';
require_once '../play/Game.php';
require_once '../play/Board.php';
require_once '../play/MoveStrategy.php';
require_once '../play/SmartStrategy.php';
require_once '../play/RandomStrategy.php';

define('STRATEGY', 'strategy');

//Supported strategies
$strategies = array(
    "Smart" => "SmartStrategy",
    "Random" => "RandomStrategy"
);

//Error checking
$response = TRUE;
//Make sure strategy is specified
if (!array_key_exists(STRATEGY, $_GET)){
    $response = FALSE;
    $errorMessage = array('response' => $response, 'reason' => "Strategy not specified");
    echo json_encode($errorMessage);
    exit;
}
//Make sure it's a supported strategy
$strategy = $_GET[STRATEGY];
if(!array_key_exists($strategy, $strategies)){
    $response = FALSE;
    $errorMessage = array('response' => $response, 'reason' => "Unknown strategy");
    echo json_encode($errorMessage);
    exit;
}

//Create Game
$board = new Board(WIDTH, HEIGHT);
$strategy = new $strategies[$strategy]($board);
$game = new Game($strategy);
$pid = uniqid();
$file = DATA_DIR . $pid . DATA_EXT;

//Encode to json string and store in data directory
if (storeState($file, $game->toJsonString())) {
    echo json_encode(array("response" => true, "pid" => $pid));
} else {
    echo "Failed to store game data";
}

?>