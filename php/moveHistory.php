<?php
  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

  $roomId = $_GET['room'];
  $onlyLatest = $_GET['onlyLatest'];

  include "./fileManager.php";

  echo '<response>';
  if($mh = readHistoryFile($roomId, $onlyLatest)) {
    for($r = 0; $r < count($mh); $r++) {
      if($mh[$r][0]) {
        echo '<move>';
        echo $mh[$r][0];
        echo '</move>';
      }
    }

    /*for($r = 0; $r < 8; $r++) {
      echo '<row id="'.$r.'">';
      for($c = 0; $c < 8; $c++) {
        echo '<col id="'.$c.'">';
        echo $bs[$r][$c];
        echo '</col>';
      }
      echo '</row>';
    }*/
  }
  echo '</response>';

?>
