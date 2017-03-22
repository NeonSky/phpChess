var selectedChessPiece;

function onChessTileClick(e) {
  if(selectedChessPiece != undefined) {
    let tile = selectedChessPiece.element.parentElement;
    moveChessPiece(tile.row, tile.col, e.target.row, e.target.col);
    //sendMoveRequest(getMoveNotation(tile.row, tile.col, e.target.row, e.target.col));
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
  latestMove = getMoveNotation(r1, c1, r2, c2);
  let chessPiece = chessTiles[r1][c1].firstChild;
  if(chessPiece) {
    if(isMoveLegal(r1, c1, r2, c2)) {
      sendMoveRequest(getMoveNotation(r1, c1, r2, c2));
      if(isCheckMate()) {

      }
      else {
        if(chessTiles[r2][c2].firstChild) { killChessPiece(r2, c2); }
        chessTiles[r2][c2].append(chessPiece);
      }
    }
    selectedChessPiece = null;
  }
}

// r: row, c: column
function killChessPiece(r, c) {
  chessTiles[r][c].removeChild(chessTiles[r][c].firstChild);
}

function getMoveNotation(r1, c1, r2, c2) {
  let from = String.fromCharCode(c1+97) + String.fromCharCode(r1+49);
  let delim = ":";
  let to = String.fromCharCode(c2+97) + String.fromCharCode(r2+49);
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

function isOpponent(r, c) {
  let chessPiece = chessTiles[r][c].firstChild
  if(chessPiece) {
    if(chessPiece.name.charAt(0) != myColor) return true;
  }
  return false;
}

function isAlly(r, c) {
  let chessPiece = chessTiles[r][c].firstChild
  if(chessPiece) {
    if(chessPiece.name.charAt(0) == myColor) return true;
  }
  return false;
}

function isOutOfBounds(r1, c1, r2, c2) {
  if(r1 < 0 || r1 > chessBoardHeight-1) { return true; }
  if(c1 < 0 || c1 > chessBoardWidth-1) { return true; }
  if(r2 < 0 || r2 > chessBoardHeight-1) { return true; }
  if(c2 < 0 || c2 > chessBoardWidth-1) { return true; }
  return false;
}

function isMoveLegal(r1, c1, r2, c2) {
  if(r1 == r2 && c1 == c2) return false;
  if(isOutOfBounds(r1, c1, r2, c2)) return false;
  if(!isOpponent(r2, c2)) { return false; }

  let chessPiece = chessTiles[r1][c1].firstChild;
  let pieceColor = chessPiece.name.charAt(0);
  if(pieceColor != myColor) return false;
  let pieceType = chessPiece.name.charAt(1);

  switch(pieceType) {
    case "k":
      return isKingMoveLegal(r1, c1, r2, c2);
      break;
    case "q":
      return isQueenMoveLegal(r1, c1, r2, c2);
      break;
    case "b":
      return isBishopMoveLegal(r1, c1, r2, c2);
      break;
    case "h":
      return isKnightMoveLegal(r1, c1, r2, c2);
      break;
    case "r":
      return isRookMoveLegal(r1, c1, r2, c2);
      break;
    case "p":
      return isPawnMoveLegal(r1, c1, r2, c2, opponent);
      break;
    default:
      return false;
      break;
  }

  return false;
}

function isKingMoveLegal(r1, c1, r2, c2) {}
function isQueenMoveLegal(r1, c1, r2, c2) {}
function isBishopMoveLegal(r1, c1, r2, c2) {}
function isKnightMoveLegal(r1, c1, r2, c2) {}
function isRookMoveLegal(r1, c1, r2, c2) {
  if(r1 == r2) {
    if(c1 < c2) {
      for(let c = c1; c <= c2; c++) {
        if(isAlly(r1, c)) { return false; }
      }
    } else {

    }
  }
  else if(c1 == c2) {

  }
  return false;
}

function isPawnMoveLegal(r1, c1, r2, c2, opponent) {
  if(opponent) {
    if(r2 == r1-1 && c2 == c1-1) { return true; }
    if(r2 == r1-1 && c2 == c1+1) { return true; }
  } else {
    if(r2 == r1-1 && c2 == c1) { return true; }
    if(r2 == r1-2 && r1 == 6 && c2 == c1) { return true; }
  }
  console.log(getMoveNotation(r1, c1, r2, c2));
  return false;
}

function isCheckMate() {
  return false;
}
