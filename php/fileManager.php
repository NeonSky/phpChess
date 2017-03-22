<?php

  // TODO: Rename board state to start state
  $defaultBoardState = array(
    array('dr','dh','db','dq','dk','db','dh','dr'),
    array('dp','dp','dp','dp','dp','dp','dp','dp'),
    array('00','00','00','00','00','00','00','00'),
    array('00','00','00','00','00','00','00','00'),
    array('00','00','00','00','00','00','00','00'),
    array('00','00','00','00','00','00','00','00'),
    array('lp','lp','lp','lp','lp','lp','lp','lp'),
    array('lr','lh','lb','lq','lk','lb','lh','lr')
  );

  function getRoomFilePath($roomId) {
    return dirname(__DIR__)."/rooms/room" . $roomId . ".csv";
  }

  function getHistoryFilePath($roomId) {
    return dirname(__DIR__)."/rooms/room" . $roomId . "-history.csv";
  }

  function resetRoomFile($roomId) {
    updateRoomFile($roomId, $GLOBALS['defaultBoardState']);
  }

  function resetHistoryFile($roomId) {
    $filePath = getHistoryFilePath($roomId);
    $file = fopen($filePath, 'w');
    fclose($file);
  }

  function openRoomFile($roomId, $mode) {
    if(is_numeric($roomId)) {
      $filePath = getRoomFilePath($roomId);
      return fopen($filePath, $mode);
    }
    else { return 0; }
  }

  function openHistoryFile($roomId, $mode) {
    if(is_numeric($roomId)) {
      $filePath = getHistoryFilePath($roomId);
      return fopen($filePath, $mode);
    }
    else { return 0; }
  }

  function updateRoomFile($roomId, $boardState) {
    if($roomFile = openRoomFile($roomId, "w")) {
      foreach($boardState as $row) {
        fputcsv($roomFile, $row);
      }
      fclose($roomFile);
      return 1;
    }
    else { return 0; }
  }

  function addCsvEntry($filePath, $entry) {
    $file = fopen($filePath, 'a');
    fputcsv($file, $entry);
    fclose($file);
  }

  function readRoomFile($roomId) {
    if(!file_exists(getRoomFilePath($roomId))) { resetRoomFile($roomId); }

    if($roomFile = openRoomFile($roomId, "r")) {
      $boardState = array();
      while(!feof($roomFile)) {
        array_push($boardState, fgetcsv($roomFile));
      }
      fclose($roomFile);
      return $boardState;
    }
    else { return 0; }
  }

  function readHistoryFile($roomId, $mode) {
    if(!file_exists(getHistoryFilePath($roomId))) { resetHistoryFile($roomId); }

    //addCsvEntry(getHistoryFilePath($roomId), ["a4:c5"]);
    //addCsvEntry(getHistoryFilePath($roomId), ["d6:f2"]);

    if($historyFile = openHistoryFile($roomId, "r")) {
      $moveHistory = array();
      if($mode == 1) {
        $lastLine = null;
        while(!feof($historyFile)) {
          $line = fgetcsv($historyFile);
          if($line) $lastLine = $line;
        }
        if($lastLine) array_push($moveHistory, $lastLine);
      }
      else if($mode == 0) {
        while(!feof($historyFile)) {
          $line = fgetcsv($historyFile);
          array_push($moveHistory, $line);
        }
      }
      fclose($historyFile);
      return $moveHistory;
    }
    else { return 0; }
  }

?>
