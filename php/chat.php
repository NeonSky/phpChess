<?php
  include "./fileManager.php";

  $roomId = isset($_GET['room']) ? $_GET['room'] : null;
  $msg = isset($_POST['msg']) ? $_POST['msg'] : null;
  $from = isset($_GET['from']) ? $_GET['from'] : null;

  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

  echo '<response>';
  if(!is_null($roomId) && !is_null($msg) && !is_null($from)) {
    addChatMessage($roomId, $msg, $from);
  }
  else if(!is_null($roomId)) {
    printChatLog();
  }
  echo '</response>';

  function printChatLog($roomId) {
    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent($filePath)) {
      $fileData = json_decode($json, true);
      for($i = 0; $i < count($fileData['chatLog']); $i++) {
        echo '<entry>';
        echo $fileData['chatLog'][$i];
        echo '</entry>';
      }
    }
  }

  function addChatMessage($roomId, $msg, $from) {
    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent($filePath)) {
      $fileData = json_decode($json, true);
      array_push($fileData['chatLog'], {"from":$from, "msg":$msg});
      file_put_contents($filePath, json_encode($fileData));
    }
  }

?>
