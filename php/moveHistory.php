<?php
  include "./fileManager.php";

  $roomId = isset($_GET['room']) ? $_GET['room'] : null;
  $mode = isset($_GET['mode']) ? $_GET['mode'] : null;
  $move = isset($_GET['move']) ? $_GET['move'] : null;
  $playerId = isset($_GET['playerId']) ? $_GET['playerId'] : null;
  $readyState = isset(€_GET['readyState']) ? $_GET['readyState'] : null;
  $checkmate = isset($_GET['checkmate']) ? $_GET['checkmate'] : null;

  header('Content-Type: text/xml');
  echo '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';

  echo '<response>';
  if(!isGameOver($roomId)) {
    if(!is_null($roomId) && !is_null($mode)) {
      printMoveHistory($roomId, $mode);
    }
    else if(!is_null($roomId) && !is_null($move) && !is_null($playerId)) {
      handleMoveRequest($roomId, $move, $playerId);
    }
    else if(!is_null($roomId) && !is_null($playerId) && !is_null($checkmate)) {
      playerLost($roomId, $playerId);
    }
  } else {
    if(!is_null($roomId) && !is_null($playerId) && !is_null($readyState)) {
      setReadyState($roomId, $playerId, $readyState);
    }
  }
  echo '</response>';


  function printMoveHistory($roomId, $mode) {
    $moveHistory = getMoveHistory($roomId, (bool)$mode);
    for($i = 0; $i < count($moveHistory); $i++) {
      echo '<move>';
      echo $moveHistory[$i];
      echo '</move>';
    }
  }

  function handleMoveRequest($roomId, $move, $playerId) {
    if(isMyTurn($roomId, $playerId)) {
      addMoveEntry($roomId, $move);
      echo "true";
    } else { echo "false"; }
  }

  function playerLost($roomId, $playerId) {
    $loserColor = getMyColor($roomId, $playerId);
    $loserColor = $loserColor == 'l' ? 'white' : 'black';
    addChatMessage(
      $roomId,
      "SERVER: Player ".$loserColor." has been checkmated and thus lost.",
      -1);

    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent($filePath)) {
      $fileData = json_decode($json, true);
      $fileData['gameEnded'] = time();
      $fileData['player1Id'] = $fileData['player2Id'] = 0;
      file_put_contents($filePath, json_encode($fileData));
    }
  }

  function setReadyState($roomId, $playerId, $readyState) {
    $filePath = getRoomFilePath($roomId);
    if($json = getFileContent($filePath)) {
      $fileData = json_decode($json, true);

      if($readyState != 0) {
        if($fileData['player1Id'] == 0) {
          $fileData['player1Id'] = $playerId;
        } else if($fileData['player2Id'] == 0 && $fileData['player1Id'] != $playerId) {
          $fileData['player2Id'] = $playerId;
        }
      } else {
        if($fileData['player1Id'] == $playerId) { $fileData['player1Id'] = 0; }
        if($fileData['player2Id'] == $playerId) { $fileData['player2Id'] = 0; }
      }

      // Start game
      if($fileData['player1Id'] != 0 && $fileData['player2Id'] != 0) {
        $fileData['gameStarted'] = time();
        $fileData['gameEnded'] = 0;
      }
      file_put_contents($filePath, json_encode($fileData));
    }
  }

  function startGame() {

  }

?>
