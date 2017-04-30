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
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="res/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="res/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="res/favicons/favicon-16x16.png">
    <link rel="manifest" href="res/favicons/manifest.json">
    <link rel="mask-icon" href="res/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">

    <!-- Styles -->
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
