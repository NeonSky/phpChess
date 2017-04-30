<?php
  include "./fileManager.php";

  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

  $roomId = isset($_GET['room']) ? $_GET['room'] : null;
  $mode = isset($_GET['mode']) ? $_GET['mode'] : null;
  //if(isset($_GET['mode'])) { $mode = $_GET['mode']; }
  $move = null;
  if(isset($_GET['move'])) { $move = $_GET['move']; }
  $playerId = null;
  if(isset($_GET['playerId'])) { $playerId = $_GET['playerId']; }

  echo '<response>';
  if(!is_null($roomId) && !is_null($mode)) {
    $moveHistory = getMoveHistory($roomId, (bool)$mode);
    for($i = 0; $i < count($moveHistory); $i++) {
      echo '<move>';
      echo $moveHistory[$i];
      echo '</move>';
    }
  }
  else if(!is_null($roomId) && !is_null($move) && !is_null($playerId)) {
    if(isMyTurn($roomId, $playerId)) { addMoveEntry($roomId, $move); }
  }
  echo '</response>';

?>
