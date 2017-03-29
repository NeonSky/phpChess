<?php

  define('defaultBoardStateFilePath',
         dirname(__DIR__)."/res/defaultBoardState.csv",
         true);

  define('defaultRoomFile', dirname(__DIR__)."/res/defaultRoomFile.json", true);

  function getRoomFilePath($roomId) {
    return dirname(__DIR__)."/rooms/room" . $roomId . ".csv";
  }

  function getRoomFilePath2($roomId) {
    return dirname(__DIR__)."/rooms/room" . $roomId . ".json";
  }

  function resetFile($filePath) {
    $file = fopen($filePath, 'w');
    fclose($file);
  }

  function resetRoomFile($roomId) {
    $filePath = getRoomFilePath2($roomId);
    if($json = file_get_contents(defaultRoomFile)) {}
      file_put_contents($filePath, $json);
    }
  }

  function openRoomFile($roomId, $mode) {
    if(is_numeric($roomId)) {
      $filePath = getRoomFilePath($roomId);
      return fopen($filePath, $mode);
    }
    else { return 0; }
  }

  function addCsvEntry($filePath, $entry) {
    $file = fopen($filePath, 'a');
    fputcsv($file, $entry);
    fclose($file);
  }

  function addMoveEntry($roomId, $move) {
    $filePath = getRoomFilePath2($roomId);
    $fileData = json_decode(file_get_contents($filePath), true);
    array_push($fileData['moveHistory'], $move);
    file_put_contents($filePath, json_encode($fileData));
  }

  function getDefaultBoardState() {
    if($file = fopen(defaultBoardStateFilePath, 'r')) {
      $defaultBoardState = array();
      while(!feof($file)) {
        array_push($defaultBoardState, fgetcsv($file));
      }
      fclose($file);
      return $defaultBoardState;
    }
  }

  function getMoveHistory($roomId, $onlyLatest) {
    $filePath = getRoomFilePath2($roomId);
    if($json = file_get_contents($filePath)) {
      $fileData = json_decode($json, true);
      $moveHistory = array();
      if($onlyLatest && count($fileData['moveHistory']) > 0) {
        array_push($moveHistory, $fileData['moveHistory'][count($fileData['moveHistory'])-1]);
      }
      else {
        for($i = 0; $i < count($fileData['moveHistory']); $i++) {
          array_push($moveHistory, $fileData['moveHistory'][$i]);
        }
      }
      return $moveHistory;
    }
    return false;
  }

?>
