<?php
  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

  $mode = null;
  $roomId = $_GET['room'];
  if(isset($_GET['mode'])) { $mode = $_GET['mode']; }
  $move = null;
  if(isset($_GET['move'])) { $move = $_GET['move']; }
  $playerId = null;
  if(isset($_GET['playerId'])) { $playerId = $_GET['playerId']; }

  include "./fileManager.php";

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
    $playerColor = getMyColor($roomId, $playerId);
    if(isMyTurn($roomId, $playerColor)) { addMoveEntry($roomId, $move); }
  }

  echo '</response>';

?>
