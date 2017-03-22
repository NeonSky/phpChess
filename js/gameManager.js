var selectedChessPiece;

function onChessTileClick(e) {
  if(selectedChessPiece != undefined) {
    let tile = selectedChessPiece.element.parentElement;
    moveChessPiece(tile.row, tile.col, e.target.row, e.target.col);
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

// r: row, c: column
function moveChessPiece(r1, c1, r2, c2) {
  let chessPiece = chessTiles[r1][c1].firstChild;
  if(chessPiece) {
    if(chessTiles[r2][c2].firstChild) { killChessPiece(r, c); }
    chessTiles[r2][c2].append(chessPiece);
    selectedChessPiece = null;
  }
}

// r: row, c: column
function killChessPiece(r, c) {
  chessTiles[r][c].removeChild(chessTiles[r][c].firstChild);
}

function getMoveNotation(r1, c1, r2, c2) {
  let from = String.fromCharCode(c1+97) + String.fromCharCode(r1+48);
  let delim = ":";
  let to = String.fromCharCode(c2+97) + String.fromCharCode(r2+48);
  return from+delim+to;
}

/* Move notation form: "c1r1:c2r2" where c is column and r is row
   (c1, r1) is the old position and (c2, r2) is the new. */
function getMoveAction(moveNotation) {
  let moveAction = {
    c1: (moveNotation.charAt(0).charCodeAt() - 97),
    r1: (moveNotation.charAt(1).charCodeAt() - 49),
    delim: moveNotation.charAt(2).charCodeAt(),
    c2: (moveNotation.charAt(3).charCodeAt() - 97),
    r2: (moveNotation.charAt(4).charCodeAt() - 49)
  }
  return moveAction;
}
