var selectedChessPiece;

function onChessTileClick(e) {
  if(selectedChessPiece != undefined) {
    moveChessPiece(selectedChessPiece, e.target.row, e.target.col, e.srcElement.firstChild != undefined);
  }
  else if(e.srcElement.firstChild != undefined) {
    selectChessPiece(e.srcElement.firstChild, e.target.row, e.target.col);
  }
}

function selectChessPiece(element, row, col) {
  selectedChessPiece = {
    element: element,
    row: row,
    col: col
  }
}

function moveChessPiece(chessPiece, r, c, isAttack) {
  if(isAttack) { chessTiles[r][c].removeChild(chessTiles[r][c].firstChild); }
  chessTiles[r][c].append(chessPiece.element);
  selectedChessPiece = null;
}
