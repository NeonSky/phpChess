<html>
  <head>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/chessBoard.css">
    <link rel="stylesheet" href="css/chatPanel.css">
    <link rel="stylesheet" href="css/actionHistory.css">
  </head>
  <body>
    <div id="actionPanel"></div>
    <div id="gamePanel">
      <div id="chessBoard"></div>
    </div>
    <div id="chatPanel"></div>
  </body>
</html>

<?php
  include "roomFileManager.php";
  include "game.php";

  $roomId = $_GET['room'];
  readRoomFile($roomId);
  //updateRoomFile($roomId, $boardState);
?>
