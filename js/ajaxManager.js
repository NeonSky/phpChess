var xmlHttp = new XMLHttpRequest();

/*
mode parameter
  0: fetch the complete move history
  1: fetch only the latest entry
*/
function fetchMoveHistory(mode, callback) {
  if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4) {
    xmlHttp.onreadystatechange = function() {
      receivedMoveHistory(mode, callback);
    }
    xmlHttp.open("GET", "php/moveHistory.php?room="+roomId+"&mode="+mode, true);
    xmlHttp.send(null);
  }
}

function receivedMoveHistory(mode, callback) {
  if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
    let xmlResponse = xmlHttp.responseXML;
    if(xmlResponse) {
      console.log(xmlResponse);
      let responseElement = xmlResponse.documentElement;
      let resChildren = responseElement.children;
      let moves = [];
      for(let i = 0; i < resChildren.length; i++) {
        moves.push(resChildren[i].innerHTML);
      }
      callback(moves);
    }
    else if(mode == 0) {
      setTimeout(function() {
        fetchMoveHistory(mode, callback);
      }, 1000);
    }
  }
}

function sendMoveRequest(move) {
  if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4) {
    xmlHttp.onreadystatechange = function() {
      receivedMoveResponse(move);
    }
    xmlHttp.open("GET", "php/moveHistory.php?room="+roomId+"&move="+move, true);
    xmlHttp.send(null);
  }
}

// TODO: remove me
function receivedMoveResponse(move) {
  if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
    let xmlResponse = xmlHttp.responseXML;
    if(xmlResponse) {
      myLatestMove = move;
      console.log(xmlResponse);
    }
  }
}
