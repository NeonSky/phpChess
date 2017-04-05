<?php

  define('defaultBoardStateFilePath',
         dirname(__DIR__)."/res/defaultBoardState.csv",
         true);

  define('defaultRoomFile', dirname(__DIR__)."/res/defaultRoomFile.json", true);

  function getRoomFilePath($roomId) {
    return dirname(__DIR__)."/rooms/room" . $roomId . ".json";
  }

  function resetFile($filePath) {
    $file = fopen($filePath, 'w');
    fclose($file);
  }

  function resetRoomFile($roomId) {
    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent(defaultRoomFile)) {
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
    $filePath = getRoomFilePath($roomId);
    $fileData = json_decode(getFileContent($filePath), true);
    $fileData['playerTurn'] = $fileData['playerTurn'] == 'l' ? 'd' : 'l';
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
    $moveHistory = array();
    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent($filePath)) {
      $fileData = json_decode($json, true);
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
    else { resetRoomFile($roomId); }
    return $moveHistory;
  }

  function getPlayerId($roomId, $playerNr) {
    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent($filePath)) {
      $fileData = json_decode($json, true);
      return $fileData['player'.$playerNr.'Id'];
    }
    return -1;
  }

  function setPlayerId($roomId, $playerNr, $playerId) {
    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent($filePath)) {
      $fileData = json_decode($json, true);
      $fileData['player'.$playerNr.'Id'] = $playerId;
      file_put_contents($filePath, json_encode($fileData));
    }
  }

  function isMyTurn($roomId, $playerColor) {
    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent($filePath)) {
      $fileData = json_decode($json, true);
      return ($fileData['playerTurn'] == $playerColor);
    }
  }

  function getMyColor($roomId, $playerId) {
    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent($filePath)) {
      $fileData = json_decode($json, true);
      if($playerId == $fileData['player1Id']) { return 'l'; }
      if($playerId == $fileData['player2Id']) { return 'd'; }
    }
    return '';
  }

  function getFileContent($filePath) {
    if(!file_exists($filePath)) {
      $file = fopen($filePath, 'w');
      fwrite($file, "");
    }
    return file_get_contents($filePath);
  }

?>
