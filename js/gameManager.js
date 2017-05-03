const roomId = '<?php echo json_encode($roomId); ?>';
const myId = '<?php echo $_COOKIE[$roomId]; ?>';
const myColor = '<?php echo getMyColor($roomId, $_COOKIE[$roomId]); ?>';
var isMyTurn = '<?php echo isMyTurn($roomId, $_COOKIE[$roomId]); ?>';

const infoPanel = document.getElementById('infoPanel');
const infoIcon = document.getElementById('infoIcon');
const actionTable = document.getElementById('actionTable');

var latestMove, myLatestMove;
var selectedChessPiece;


// Driver program
startGame();

function startGame() {
  updateInfoPanel();
  buildChessBoard();
  resetChessPieces();
  fetchMoveHistory(0, loadMoveHistory);
  //fetchChatLog(loadChatLog);
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

function loadChatLog() {

}

function resetChessPieces() {
  let defaultBoardState = <?php echo json_encode(getDefaultBoardState()); ?>;
  for(let r = 0; r < chessBoardHeight; r++) {
    for(let c = 0; c < chessBoardWidth; c++) {
      let name = defaultBoardState[r][c];
      if(name != undefined) { createChessPiece(name, r, c); }
    }
  }
}
