<?php

  define('defaultBoardStateFilePath',
         dirname(__DIR__)."/res/defaultBoardState.csv", true);

  define('defaultRoomFile',
         dirname(__DIR__)."/res/defaultRoomFile.json", true);

  function getRoomFilePath($roomId) {
    return dirname(__DIR__)."/server/rooms/room" . $roomId . ".json";
  }

  function resetFile($filePath) {
    $file = fopen($filePath, 'w');
    fclose($file);
    chmod($filePath, 0777);
  }

  function getFileContent($filePath) {
    if(!file_exists($filePath)) { resetFile($filePath); }
    return file_get_contents($filePath);
  }

  function getRoomContent($roomId) {
    $json = getFileContent(getRoomFilePath($roomId));
    if(!$json) { $json = resetRoomFile($roomId); }
    return $json;
  }

  function resetRoomFile($roomId) {
    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent(defaultRoomFile)) {
      file_put_contents($filePath, $json);
    }
    return $json;
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
    if($json = getRoomContent($roomId)) {
      $fileData = json_decode($json, true);
      if($fileData['playerTurn'] == 'l') {
        $fileData['player1Time'] += time()-$fileData['turnStarted'];
      } else if($fileData['playerTurn'] == 'd') {
        $fileData['player2Time'] += time()-$fileData['turnStarted'];
      }
      $fileData['turnStarted'] = time();

      $fileData['playerTurn'] = ($fileData['playerTurn'] == 'l') ? 'd' : 'l';
      array_push($fileData['moveHistory'], $move);
      file_put_contents($filePath, json_encode($fileData));
    }
  }

  function addChatMessage($roomId, $msg, $from) {
    $filePath = getRoomFilePath($roomId);
    if($json = getRoomContent($roomId)) {
      $fileData = json_decode($json, true);
      $chatEntry = createChatEntry($from, $msg);
      array_push($fileData['chatLog'], $chatEntry);
      file_put_contents($filePath, json_encode($fileData));
    }
  }

  function createChatEntry($from, $msg) {
    $chatEntry = new stdClass();
    $chatEntry->from = $from;
    $chatEntry->msg = $msg;
    $chatEntry->time = time();
    return $chatEntry;
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
    if($json = getRoomContent($roomId)) {
      $fileData = json_decode($json, true);
      if($onlyLatest && count($fileData['moveHistory']) > 0) {
        array_push($moveHistory,
                   $fileData['moveHistory'][count($fileData['moveHistory'])-1]);
      }
      else {
        for($i = 0; $i < count($fileData['moveHistory']); $i++) {
          array_push($moveHistory, $fileData['moveHistory'][$i]);
        }
      }
      return $moveHistory;
    }
    return $moveHistory;
  }

  function getPlayerId($roomId, $playerNr) {
    if($json = getRoomContent($roomId)) {
      $fileData = json_decode($json, true);
      return $fileData['player'.$playerNr.'Id'];
    }
    return -1;
  }

  function setPlayerId($roomId, $playerNr, $playerId) {
    $filePath = getRoomFilePath($roomId);
    if($json = getRoomContent($roomId)) {
      $fileData = json_decode($json, true);
      $fileData['player'.$playerNr.'Id'] = $playerId;
      // Start game
      if($fileData['player1Id'] != 0 && $fileData['player2Id'] != 0) {
        $fileData['gameStarted'] = $fileData['turnStarted'] = time();
        $fileData['gameEnded'] = 0;
      }
      file_put_contents($filePath, json_encode($fileData));
    }
  }

  function isMyTurn($roomId, $playerId) {
    $playerColor = getMyColor($roomId, $playerId);
    if($json = getRoomContent($roomId)) {
      $fileData = json_decode($json, true);
      return ($fileData['playerTurn'] == $playerColor);
    }
    return 0;
  }

  function getMyColor($roomId, $playerId) {
    if($json = getRoomContent($roomId)) {
      $fileData = json_decode($json, true);
      if($playerId == $fileData['player1Id']) { return 'l'; }
      if($playerId == $fileData['player2Id']) { return 'd'; }
    }
    return '';
  }

  function isGameOver($roomId) {
    if($json = getRoomContent($roomId)) {
      $fileData = json_decode($json, true);
      return $fileData['gameEnded'] > 0;
    }
    return true;
  }

?>
