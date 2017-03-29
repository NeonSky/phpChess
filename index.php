<?php
  // Global Variables Setup
  $roomId = $_GET['room'];

  include "./php/cookieManager.php";
?>

<!DOCTYPE html>
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
      <?php include "./html/leftBorder.php"; ?>
      <div id="chessBoard"></div>
      <?php include "./html/bottomBorder.php"; ?>
    </div>
    <div id="chatPanel"></div>
  </body>
</html>

<?php
  include "./php/fileManager.php";
  include "./php/game.php";
?>
