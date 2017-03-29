<?php
  $maxId = 100000;
  $cookieTTL = 2*60*60;

  if(!isset($_COOKIE[$roomId])) {
    for($i = 1; $i <= 2; $i++) {
      $playerId = getPlayerId($roomId, $i);
      if($playerId == 0) {
        $playerId = rand(1, $maxId);
        setPlayerId($roomId, $i, $playerId);
        setcookie($roomId, $playerId, time()+$cookieTTL);
        break;
      }
    }
  }

?>
