<?php
  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

  $roomId = $_GET['room'];
  $mode = null;
  if(isset($_GET['mode'])) { $mode = $_GET['mode']; }
  $move = null;
  if(isset($_GET['move'])) { $move = $_GET['move']; }

  include "./fileManager.php";

  echo '<response>';

  if(!is_null($roomId) && !is_null($mode)) {
    if($mh = readHistoryFile($roomId, $mode)) {
      for($r = 0; $r < count($mh); $r++) {
        for($c = 0; $c < count($mh[$r]); $c++) {
          if($mh[$r][$c]) {
            echo '<move>';
            echo $mh[$r][$c];
            echo '</move>';
          }
        }
      }
    }
  }
  else if(!is_null($roomId) && !is_null($move)) {
    echo $move;
    addCsvEntry(getHistoryFilePath($roomId), [$move]);
  }

  echo '</response>';

?>
