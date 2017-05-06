var latestChatMessage;
var latestMove, myLatestMove;
var selectedChessPiece;


function startGame() {
  updateInfoPanel();
  buildChessBoard();
  resetChessPieces();
  setInterval(update, 200);
}

function update() {
  fetchMoveHistory(loadMoveHistory);
  fetchChatLog(updateChatPanel);
}

function loadMoveHistory(moveHistory) {
  if(moveHistory == undefined || latestMove == moveHistory[0]) return;
  for(let i = 0; i < moveHistory.length; i++) {
    let move = getMoveAction(moveHistory[i]);
    moveChessPiece(move.r1, move.c1, move.r2, move.c2);
  }
  updateActionPanel(moveHistory);
}

function resetChessPieces() {
  for(let r = 0; r < chessBoardHeight; r++) {
    for(let c = 0; c < chessBoardWidth; c++) {
      let name = defaultBoardState[r][c];
      if(name != undefined) { createChessPiece(name, r, c); }
    }
  }
}
