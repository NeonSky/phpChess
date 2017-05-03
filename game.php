<?php
  // Globals
  $page = "game";
  $roomId = $_GET['room'];

  // Pre-HTML
  include "./php/fileManager.php";
  include "./php/cookieManager.php";

  // HTML
  include "./components/html.php";

  // Post-HTML
  include "./php/game.php";
?>
