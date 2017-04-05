<?php
  $maxId = 100000;
  $cookieTTL = 5*60;//2*60*60; // 2h

  if(!isset($_COOKIE[$roomId])) { assignCookie(); }
  //echo $_COOKIE[$roomId];

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
