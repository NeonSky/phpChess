<?php
  $maxId = 100000;
  $cookieTTL = 24*60*60; // 24h
  if(!isset($_COOKIE[$roomId])) { assignCookie(); }

  function assignCookie() {
    $cookieValue = -1;
    for($i = 1; $i <= 2; $i++) {
      $playerId = getPlayerId($GLOBALS['roomId'], $i);
      if($playerId == 0) {
        $playerId = rand(1, $GLOBALS['maxId']);
        setPlayerId($GLOBALS['roomId'], $i, $playerId);
        $cookieValue = $playerId;
        break;
      }
    }

    if($cookieValue != -1) {
      setcookie($GLOBALS['roomId'], $cookieValue, time() + $GLOBALS['cookieTTL']);
    }
    $_COOKIE[$GLOBALS['roomId']] = $cookieValue;
  }

?>
