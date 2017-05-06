const infoPanel = document.getElementById('infoPanel');
const infoIcon = document.getElementById('infoIcon');
const actionTable = document.getElementById('actionTable');
const chatForm = document.getElementById('chatForm');
chatForm.addEventListener("submit", sendChatMessage, false);
const chatBox = document.getElementById('chatBox');

var latestMove, myLatestMove;
var selectedChessPiece;

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
  for(let r = 0; r < chessBoardHeight; r++) {
    for(let c = 0; c < chessBoardWidth; c++) {
      let name = defaultBoardState[r][c];
      if(name != undefined) { createChessPiece(name, r, c); }
    }
  }
}
