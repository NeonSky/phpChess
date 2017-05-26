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
const chessBoard = document.getElementById('chessBoard');
var chessTiles;
var coloredTiles = [];

var myKingPos = {};

function buildChessBoard() {
  // Reset chessTiles
  chessTiles = new Array(chessBoardWidth)
  for(var i = 0; i < chessBoardWidth; i++) {
    chessTiles[i] = new Array(chessBoardHeight);
  }

  // Create tiles
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

function createChessPiece(dbName, r, c) {
  let imgName = dbToImgName[dbName];
  if(imgName != undefined) {
    var chessPiece = appendElement(chessTiles[r][c], 'img', 'chessPiece');
    chessPiece.name = dbName;
    chessPiece.src = './res/pieces/' + imgName + '.svg';
    chessPieces.push({
      "team": dbName[0],
      "type": dbName[1],
      "pos": {"r": r, "c": c}
    });
    storeKingMovements(chessPiece, r, c);
  }
  return chessPiece;
}

function onChessTileClick(e) {
  if(selectedChessPiece != undefined) {
    let tile = selectedChessPiece.element.parentElement;
    let chessPiece = chessTiles[tile.row][tile.col].firstChild;
    let pieceColor = chessPiece.name.charAt(0);
    if(pieceColor != myColor) { return; }
    if(isMoveLegal(tile.row, tile.col, e.target.row, e.target.col)) {
      sendMoveRequest(getMoveNotation(tile.row, tile.col,
                                      e.target.row, e.target.col));
    }
    selectedChessPiece = undefined;
  }
  else if(e.srcElement.firstChild != undefined) {
    selectChessPiece(e.srcElement.firstChild, e.target.row, e.target.col);
    colorTiles(e);
  }
}

function colorTiles(e) {
  return;
  clearSelection();
  let tile = selectedChessPiece.element.parentElement;
  // King version
  for(let c = -1; c <= 1; c++) {
    for(let r = -1; r <= 1; r++) {
      if(r == 0 && c == 0) { continue; }
      coloredTiles.push(chessTiles[tile.row+r][tile.col+c]);
      chessTiles[tile.row+r][tile.col+c].prevClass = chessTiles[tile.row+r][tile.col+c].className;
      chessTiles[tile.row+r][tile.col+c].className += " selectedTile";
    }
  }
}

function clearSelection() {
  for(let i = 0; i < coloredTiles.length; i++) {
    coloredTiles[i].className = coloredTiles[i].prevClass;
  }
  coloredTiles = [];
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
    if(chessTiles[r2][c2].firstChild) { killChessPiece(r2, c2); }
    chessTiles[r2][c2].append(chessPiece);
    selectedChessPiece = null;
    for(let i = 0; i < chessPieces.length; i++) {
      if(chessPieces[i].pos.r == r1 && chessPieces[i].pos.c == c1) {
        chessPieces[i].pos = {"r":r2, "c":c2};
        break;
      }
    }
    storeKingMovements(chessPiece, r2, c2);
    if(chessPiece.name[0] != myColor) { sendCheckStatus({"r":r2, "c":c2}); }
  }
  if(latestMove == myLatestMove) { isMyTurn = false; }
  else { myTurn = true; }
}

function storeKingMovements(chessPiece, r, c) {
  if((chessPiece.name == "lk" && myColor == "l") ||
     (chessPiece.name == "dk" && myColor == "d")) {
    myKingPos = {"r":r, "c":c};
  }
}

function sendCheckStatus(movedPiecePos) {
  checkingPiecePos = movedPiecePos;
  if(isCheck()) {
    if(isCheckMate()) { sendCheckMate(); }
    else { sendCheck(); }
  }
}

function isCheck() {
  return isMoveLegal(checkingPiecePos.r, checkingPiecePos.c,
                     myKingPos.r, myKingPos.c);
}

// Note: assumes the king is already in check
function isCheckMate() {
  if(isCheckEscapable()) { return false; }
  console.log("Not escapable!");
  if(isCheckBlockable()) { return false; }
  console.log("Not blockable!");
  if(isCheckCapturable()) { return false; }
  console.log("Not capturable!");
  return true;
}

// For every possible escape position, check if it's safe
function isCheckEscapable() {
  for(let r = myKingPos.r-1; r < myKingPos.r+1; r++) {
    for(let c = myKingPos.c-1; c < myKingPos.c+1; c++) {
      if(r == c) { continue; }
      if(isMoveLegal(myKingPos.r, myKingPos.c, r, c)) {
        let isPositionSafe = true;
        for(let i = 0; i < chessPieces.length; i++) {
          if(isMoveLegal(chessPieces[i].pos.r, chessPieces[i].pos.c, r, c)) {
            isPositionSafe = false;
            break;
          }
        }
        if(isPositionSafe) { return true; }
      }
    }
  }
  return false;
}

// For every ally piece, check every possible move and check if it can block
function isCheckBlockable() {
  for(let i = 0; i < chessPieces.length; i++) {
    if(chessPieces[i].team != myColor) { continue; }
    for(let r = 0; r < chessBoardHeight; r++) {
      for(let c = 0; c < chessBoardWidth; c++) {
        if(isMoveLegal(chessPieces[i].pos.r, chessPieces[i].pos.c, r, c)) {
          if(!isCheck()) { return true; }
        }
      }
    }
  }
  return false;
}

// For every ally piece, check if it can capture the checking piece
function isCheckCapturable() {
  for(let i = 0; i < chessPieces.length; i++) {
    if(chessPieces[i].team != myColor) { continue; }
    if(isMoveLegal(chessPieces[i].pos.r, chessPieces[i].pos.c,
                      checkingPiecePos.r, checkingPiecePos.c)) {
      return true;
    }
  }
  return false;
}

// r: row, c: column
function killChessPiece(r, c) {
  chessTiles[r][c].removeChild(chessTiles[r][c].firstChild);
  for(let i = 0; i < chessPieces.length; i++) {
    if(chessPieces[i].pos.r == r && chessPieces[i].pos.c == c) {
      chessPieces.splice(i, 1);
      break;
    }
  }
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
    if(chessPiece.name.charAt(0) != myColor) { return true; }
  }
  return false;
}

function isAlly(r, c) {
  let chessPiece = chessTiles[r][c].firstChild
  if(chessPiece) {
    if(chessPiece.name.charAt(0) == myColor) { return true; }
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
  //console.log(c1 + ":" + r1 + ", " + c2 + ":" + r2);
  if(r1 == r2 && c1 == c2) return false;
  if(isOutOfBounds(r1, c1, r2, c2)) return false;
  if(isAlly(r1, c1) && isAlly(r2, c2)) { return false; }

  let chessPiece = chessTiles[r1][c1].firstChild;
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
      return isPawnMoveLegal(r1, c1, r2, c2);
      break;
    default:
      return false;
      break;
  }

  return false;
}

function isKingMoveLegal(r1, c1, r2, c2) {
  return (Math.abs(r1 - r2) <= 1 && Math.abs(c1 - c2) <= 1 && !isAlly(r2, c2));
}

function isQueenMoveLegal(r1, c1, r2, c2) {
  return (isRookMoveLegal(r1, c1, r2, c2) || isBishopMoveLegal(r1, c1, r2, c2));
}

function isBishopMoveLegal(r1, c1, r2, c2) {
  if(Math.abs(r1 - r2) != Math.abs(c1 - c2)) { return false; }
  if(r1 < r2) {
    if(c1 < c2) {
      for(let i = 1; c1+i <= c2 && r1+i <= r2; i++) {
        if(isAlly(r1+i, c1+i)) { return false; }
      }
      return true;
    }
    else if(c2 < c1) {
      for(let i = 1; c2+i < c1 && r1+i <= r2; i++) {
        if(isAlly(r1+i, c1-i)) { return false; }
      }
      return true;
    }
  }
  else if(r2 < r1) {
    if(c1 < c2) {
      for(let i = 1; c1+i <= c2 && r2+i < r1; i++) {
        if(isAlly(r1-i, c1+i)) { return false; }
      }
      return true;
    }
    else if(c2 < c1) {
      for(let i = 1; c2+i < c1 && r2+i < r1; i++) {
        if(isAlly(r1-i, c1-i)) { return false; }
      }
      return true;
    }
  }
  return false;
}

function isKnightMoveLegal(r1, c1, r2, c2) {
  if(r1+1 == r2) {
    if(c1+2 == c2) { return !isAlly(r2, c2); }
    if(c1-2 == c2) { return !isAlly(r2, c2); }
  }
  else if(r1-1 == r2) {
    if(c1+2 == c2) { return !isAlly(r2, c2); }
    if(c1-2 == c2) { return !isAlly(r2, c2); }
  }
  if(r1+2 == r2) {
    if(c1+1 == c2) { return !isAlly(r2, c2); }
    if(c1-1 == c2) { return !isAlly(r2, c2); }
  }
  else if(r1-2 == r2) {
    if(c1+1 == c2) { return !isAlly(r2, c2); }
    if(c1-1 == c2) { return !isAlly(r2, c2); }
  }
  return false;
}

function isRookMoveLegal(r1, c1, r2, c2) {
  if(r1 == r2) {
    if(c1 < c2) {
      for(let c = c1+1; c <= c2; c++) {
        if(isAlly(r1, c)) { return false; }
      }
      return true;
    } else {
      for(let c = c2; c < c1; c++) {
        if(isAlly(r1, c)) { return false; }
      }
      return true;
    }
  }
  else if(c1 == c2) {
    if(r1 < r2) {
      for(let r = r1+1; r <= r2; r++) {
        if(isAlly(r, c1)) { return false; }
      }
      return true;
    } else {
      for(let r = r2; r < r1; r++) {
        if(isAlly(r, c1)) { return false; }
      }
      return true;
    }
  }
  return false;
}

function isPawnMoveLegal(r1, c1, r2, c2) {
  let dir = myColor == "l" ? 1 : -1;
  let startRow = dir == 1 ? 6 : 1;
  if(isOpponent(r2, c2)) {
    if(r2 == r1-dir && c2 == c1-dir) { return true; }
    if(r2 == r1-dir && c2 == c1+dir) { return true; }
  } else {
    if(r2 == r1-dir && c2 == c1) { return true; }
    if(r2 == r1-2*dir && r1 == startRow && c2 == c1) { return true; }
  }
  //console.log(getMoveNotation(r1, c1, r2, c2));
  return false;
}
