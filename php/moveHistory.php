<?php
  include "./fileManager.php";

  $roomId = isset($_GET['room']) ? $_GET['room'] : null;
  $mode = isset($_GET['mode']) ? $_GET['mode'] : null;
  $move = isset($_GET['move']) ? $_GET['move'] : null;
  $playerId = isset($_GET['playerId']) ? $_GET['playerId'] : null;

  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

  echo '<response>';
  if(!is_null($roomId) && !is_null($mode)) {
    printMoveHistory($roomId, $mode);
  }
  else if(!is_null($roomId) && !is_null($move) && !is_null($playerId)) {
    handleMoveRequest($roomId, $move, $playerId);
  }
  echo '</response>';


  function printMoveHistory($roomId, $mode) {
    $moveHistory = getMoveHistory($roomId, (bool)$mode);
    for($i = 0; $i < count($moveHistory); $i++) {
      echo '<move>';
      echo $moveHistory[$i];
      echo '</move>';
    }
  }

  function handleMoveRequest($roomId, $move, $playerId) {
    if(isMyTurn($roomId, $playerId)) {
      addMoveEntry($roomId, $move);
      echo "true";
    } else { echo "false"; }
  }

?>
