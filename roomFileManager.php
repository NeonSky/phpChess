<?php

  $defaultBoardState = array(
    array('dr','dk','db','dq','dk','db','dk','dr'),
    array('dp','dp','dp','dp','dp','dp','dp','dp'),
    array('00','00','00','00','00','00','00','00'),
    array('00','00','00','00','00','00','00','00'),
    array('00','00','00','00','00','00','00','00'),
    array('00','00','00','00','00','00','00','00'),
    array('lp','lp','lp','lp','lp','lp','lp','lp'),
    array('lr','lk','lb','lq','lk','lb','lk','lr')
  );

  function getRoomFilePath($roomId) {
    return "rooms/room" . $roomId . ".csv";
  }

  function resetRoomFile($roomId) {
    updateRoomFile($roomId, $GLOBALS['defaultBoardState']);
  }

  function openRoomFile($roomId, $mode) {
    if(is_numeric($roomId)) {
      $filePath = getRoomFilePath($roomId);
      return fopen($filePath, $mode);
    }
    else { return 0; }
  }

  function updateRoomFile($roomId, $boardState) {
    if($roomFile = openRoomFile($roomId, "w")) {
      foreach ($boardState as $row) {
        fputcsv($roomFile, $row);
      }
      fclose($roomFile);
      return 1;
    }
    else { return 0; }
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

?>
