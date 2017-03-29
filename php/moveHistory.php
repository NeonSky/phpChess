<?php
  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

  $mode = null;
  $roomId = $_GET['room'];
  if(isset($_GET['mode'])) { $mode = $_GET['mode']; }
  $move = null;
  if(isset($_GET['move'])) { $move = $_GET['move']; }

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
  else if(!is_null($roomId) && !is_null($move)) {
    echo $move;
    //addCsvEntry(getRoomFilePath($roomId), [$move]);
    addMoveEntry($roomId, $move);
  }

  echo '</response>';

?>
