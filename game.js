const chessBoardWidth = 8;
const chessBoardHeight = 8;

var chessBoard = document.getElementById('chessBoard');
var chessTiles = new Array(chessBoardWidth);
for(var i = 0; i < chessBoardWidth; i++) chessTiles[i] = new Array(chessBoardHeight);

var selectedChessPiece;

initChessBoard();
initChessPieces();


function initChessBoard() {
  for(var y = 0; y < chessBoardHeight; y++) {
    var chessRow = appendElement(chessBoard, 'div', 'chessRow');
    for(var x = 0; x < chessBoardWidth; x++) {
      chessTiles[x][y] = appendElement(chessRow, 'div', 'chessTile ' + ((x%2+y%2 == 1) ? 'lightTile':'darkTile'));
      chessTiles[x][y].addEventListener('click', function(x, y) {
        onChessTileClick(x, y);
      }, false);
    }
  }
}

function initChessPieces() {
  // Dark side
  createChessPiece('dark-rook', 0, 0);
  createChessPiece('dark-knight', 1, 0);
  createChessPiece('dark-bishop', 2, 0);
  createChessPiece('dark-queen', 3, 0);
  createChessPiece('dark-king', 4, 0);
  createChessPiece('dark-bishop', 5, 0);
  createChessPiece('dark-knight', 6, 0);
  createChessPiece('dark-rook', 7, 0);
  for(var i = 0; i < 8; i++) createChessPiece('dark-pawn', i, 1);

  // Light side
  createChessPiece('light-rook', 0, 7);
  createChessPiece('light-knight', 1, 7);
  createChessPiece('light-bishop', 2, 7);
  createChessPiece('light-queen', 3, 7);
  createChessPiece('light-king', 4, 7);
  createChessPiece('light-bishop', 5, 7);
  createChessPiece('light-knight', 6, 7);
  createChessPiece('light-rook', 7, 7);
  for(var i = 0; i < 8; i++) createChessPiece('light-pawn', i, 6);
}

function onChessTileClick(x, y) {
  console.log(x + " " + y);
  /*if(e.srcElement.firstChild == undefined && selectedChessPiece != undefined) {
    moveChessPiece(selectedChessPiece);
  }
  else if(e.srcElement.firstChild != undefined) {
    selectChessPiece(e.srcElement);
  }*/

}

function selectChessPiece(chessPiece) {
  selectedChessPiece = chessPiece;
}

function createChessPiece(type, x, y) {
  var chessPiece = appendElement(chessTiles[x][y], 'img', 'chessPiece');
  chessPiece.src = './Resources/'+type+'.svg';
  return chessPiece;
}

function moveChessPiece(chessPiece, x, y, isAttack) {
  if(isAttack) chessTiles[x][y].removeChild(chessTiles[x][y].firstChild);
  chessTiles[x][y].append(chessPiece);
}

// Helper functions a.k.a. functions that make life easier.
function appendElement(parent, type, classname) {
  var child = document.createElement(type);
  child.className = classname;
  parent.append(child);
  return child;
}
