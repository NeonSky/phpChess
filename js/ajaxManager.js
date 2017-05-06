var xmlHttp = new XMLHttpRequest();

function fetchChatLog(callback) {
  if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4) {
    xmlHttp.onreadystatechange = function() {
      receivedChatLog(callback);
    }
    //xmlHttp.open("POST", "");
    xmlHttp.send(null);
  }
}

function receivedChatLog(callback) {

}

function sendChatMessage(e) {
  e.preventDefault();
  if(chatBox == undefined) return;

  let chatMsg = chatBox.value;
  chatBox.value = "";
  var xmlHttp2 = new XMLHttpRequest();
  if(xmlHttp2.readyState == 0 || xmlHttp2.readyState == 4) {
    xmlHttp2.onreadystatechange = function() {
      if(xmlHttp2.readyState == 4 && xmlHttp2.status == 200) {
        let xmlResponse = xmlHttp2.responseXML;
        if(xmlResponse) {
          console.log(xmlResponse.documentElement);
        }
      }
    }
    xmlHttp2.open("POST", "php/chat.php", true);
    xmlHttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlHttp2.send("msg=" + chatMsg + "&from=" + myId + "&room=" + roomId);
  }
}

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
    xmlHttp.open("GET", "php/moveHistory.php?room="+roomId+"&move="+move+"&playerId="+myId, true);
    xmlHttp.send(null);
  }
}

function receivedMoveResponse(move) {
  if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
    let xmlResponse = xmlHttp.responseXML;
    if(xmlResponse) {
      if(xmlResponse.documentElement.innerHTML == "true") {
        isMyTurn = false;
        myLatestMove = move;
      }
    }
  }
}
