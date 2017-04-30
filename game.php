<?php
  // Global Variables Setup
  $roomId = $_GET['room'];
  
  include "./php/fileManager.php";
  include "./php/cookieManager.php";
?>

<!DOCTYPE html>
<html>
  <head>
    <title>phpChess</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/chessBoard.css">
    <link rel="stylesheet" href="css/chatPanel.css">
    <link rel="stylesheet" href="css/actionHistory.css">
  </head>
  <body>
    <?php include "./html/header.html"; ?>
    <div id="content">
      <?php include "./html/actionPanel.html"; ?>
      <?php include "./html/gamePanel.html"; ?>
      <?php include "./html/chatPanel.html"; ?>
    </div>
  </body>
</html>

<?php include "./php/game.php"; ?>
