<script type="text/javascript" src="js/domManager.js"></script>
<script type="text/javascript" src="js/ajaxManager.js"></script>
<script type="text/javascript" src="js/gameManager.js"></script>

<script type="text/javascript">

  // Look-up table for database name to image name
  const dbToImgName = {
    'dk': 'dark-king',
    'dq': 'dark-queen',
    'db': 'dark-bishop',
    'dh': 'dark-knight',
    'dr': 'dark-rook',
    'dp': 'dark-pawn',
    'lk': 'light-king',
    'lq': 'light-queen',
    'lb': 'light-bishop',
    'lh': 'light-knight',
    'lr': 'light-rook',
    'lp': 'light-pawn'
  };

  const chessBoardWidth = 8;
  const chessBoardHeight = 8;

  const roomId = <?php echo json_encode($roomId); ?>;
  const actionTable = document.getElementById('actionTable');
  const chessBoard = document.getElementById('chessBoard');
  var chessTiles = new Array(chessBoardWidth);
  for(var i = 0; i < chessBoardWidth; i++) chessTiles[i] = new Array(chessBoardHeight);

  const myId = '<?php echo $_COOKIE[$roomId]; ?>';
  const myColor = '<?php echo getMyColor($roomId, $_COOKIE[$roomId]); ?>';
  console.log("my id: " + myId + ", my color: " + myColor);
  var latestMove, myLatestMove;
  var isMyTurn = '<?php echo isMyTurn($roomId, $_COOKIE[$roomId]); ?>';

  startGame();

  function startGame() {
    buildChessBoard();
    spawnChessPieces();
    fetchMoveHistory(0, loadMoveHistory);
    setInterval(function() { fetchMoveHistory(1, loadMoveHistory); }, 200);
  }

  function loadMoveHistory(moveHistory) {
    if(moveHistory == undefined || latestMove == moveHistory[0]) return;
    for(let i = 0; i < moveHistory.length; i++) {
      let move = getMoveAction(moveHistory[i]);
      moveChessPiece(move.r1, move.c1, move.r2, move.c2);
    }
    updateActionPanel(moveHistory);
  }

  function updateActionPanel(moveHistory) {
    let tbody = actionTable.children[0];
    let lastRow = tbody.children[tbody.children.length-1];
    let entriesAdded = 0;
    while(entriesAdded < moveHistory.length) {
      if(lastRow.children.length >= 3) {
        lastRow = appendElement(tbody, 'tr', '');
      } else {
        if(lastRow.children.length == 0) {
          let rowNumber = appendElement(lastRow, 'td');
          rowNumber.innerHTML = (tbody.children.length-1)+".";
        }
        let entry = appendElement(lastRow, 'td');
        entry.innerHTML = moveHistory[entriesAdded].toUpperCase();
        entriesAdded++;
      }
    }
  }

  function buildChessBoard() {
    for(let r = 0; r < chessBoardHeight; r++) {
      var chessRow = appendElement(chessBoard, 'div', 'chessRow');
      for(let c = 0; c < chessBoardWidth; c++) {
        chessTiles[r][c] = appendElement(chessRow, 'div', 'chessTile ' + ((r%2+c%2 == 1) ? 'darkTile':'lightTile'));
        chessTiles[r][c].row = r;
        chessTiles[r][c].col = c;
        chessTiles[r][c].addEventListener('click', onChessTileClick, false);
      }
    }
  }

  function spawnChessPieces() {
    let defaultBoardState = <?php echo json_encode(getDefaultBoardState()); ?>;

    for(let r = 0; r < chessBoardHeight; r++) {
      for(let c = 0; c < chessBoardWidth; c++) {
        let name = defaultBoardState[r][c];
        if(name != undefined) { createChessPiece(name, r, c); }
      }
    }
  }

  function createChessPiece(dbName, r, c) {
    let imgName = dbToImgName[dbName];
    if(imgName != undefined) {
      var chessPiece = appendElement(chessTiles[r][c], 'img', 'chessPiece');
      chessPiece.name = dbName;
      chessPiece.src = './res/pieces/' + imgName + '.svg';
    }
    return chessPiece;
  }

</script>
