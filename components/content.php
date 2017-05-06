<?php
  switch($page) {
    case "game":
      include "game/content.php";
      break;
    default:
      include "index/content.php";
      break;
  }
?>
