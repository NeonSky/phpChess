<script type="text/javascript" src="js/domManager.js"></script>
<script type="text/javascript" src="js/gameManager.js"></script>

<script type="text/javascript">

  // Look-up table for database name to image name
  var dbToImgName = {
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
  }

  const chessBoardWidth = 8;
  const chessBoardHeight = 8;

  var chessBoard = document.getElementById('chessBoard');
  var chessTiles = new Array(chessBoardWidth);
  for(var i = 0; i < chessBoardWidth; i++) chessTiles[i] = new Array(chessBoardHeight);

  startGame();

  function startGame() {
    buildChessBoard();
    spawnChessPieces();
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
    let boardState = <?php echo json_encode(readRoomFile($roomId)); ?>;

    for(let r = 0; r < chessBoardHeight; r++) {
      for(let c = 0; c < chessBoardWidth; c++) {
        let name = dbToImgName[boardState[r][c]];
        if(name != undefined) createChessPiece(name, r, c);
      }
    }
  }

  function createChessPiece(name, r, c) {
    var chessPiece = appendElement(chessTiles[r][c], 'img', 'chessPiece');
    chessPiece.src = './res/' + name + '.svg';
    return chessPiece;
  }

</script>
