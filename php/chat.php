<?php
  include "./fileManager.php";

  $roomId = isset($_POST['room']) ? $_POST['room'] : null;
  $msg = isset($_POST['msg']) ? $_POST['msg'] : null;
  $from = isset($_POST['from']) ? $_POST['from'] : null;
  $onlyLatest = isset($_POST['onlyLatest']) ? $_POST['onlyLatest'] : null;

  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

  echo '<response>';
  echo $onlyLatest;
  if(!is_null($roomId) && !is_null($msg) && !is_null($from)) {
    addChatMessage($roomId, $msg, $from);
  } else if(!is_null($roomId) && !is_null($onlyLatest)) {
    printChatLog($roomId, $onlyLatest);
  }
  echo '</response>';

  function printChatLog($roomId, $onlyLatest) {
    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent($filePath)) {
      $fileData = json_decode($json, true);

      if($onlyLatest == "true") {
        printChatEntry($fileData['chatLog'][count($fileData['chatLog'])-1]);
      } else {
        for($i = 0; $i < count($fileData['chatLog']); $i++) {
          printChatEntry($fileData['chatLog'][$i]);
        }
      }
    }
  }

  function printChatEntry($entry) {
    echo '<entry>';
    echo '<msg>'.$entry['msg'].'</msg>';
    echo '<from>'.$entry['from'].'</from>';
    echo '<time>'.$entry['time'].'</time>';
    echo '</entry>';
  }

?>
