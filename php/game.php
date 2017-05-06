<script type="text/javascript">
  // Game data from server
  const defaultBoardState = <?php echo json_encode(getDefaultBoardState()); ?>;
  const roomId = <?php echo json_encode($roomId); ?>;
  const myId = '<?php echo $_COOKIE[$roomId]; ?>';
  const myColor = '<?php echo getMyColor($roomId, $_COOKIE[$roomId]); ?>';
  var isMyTurn = '<?php echo isMyTurn($roomId, $_COOKIE[$roomId]); ?>';
</script>

<script type="text/javascript" src="js/domManager.js"></script>
<script type="text/javascript" src="js/ajaxManager.js"></script>
<script type="text/javascript" src="js/boardManager.js"></script>
<script type="text/javascript" src="js/gameManager.js"></script>

<script type="text/javascript">
  // Driver program
  startGame();
</script>
