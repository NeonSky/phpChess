const postHeaderName = "Content-type";
const postHeaderValue = "application/x-www-form-urlencoded";

function loadAjaxDoc(callback, doc, method, msg=null) {
  let xmlHttp = new XMLHttpRequest();
  if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4) {
    xmlHttp.onreadystatechange = function() {
      if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        let xmlResponse = xmlHttp.responseXML;
        if(xmlResponse && callback) { callback(xmlResponse.documentElement); }
      }
    }
    xmlHttp.open(method, doc, true);
    if(method == "POST") {
      xmlHttp.setRequestHeader(postHeaderName, postHeaderValue);
    }
    xmlHttp.send(msg);
  }
}

function fetchChatLog(callback) {
  let onlyLatest = (latestChatMessage != undefined);
  let msg = "room=" + roomId + "&onlyLatest=" + onlyLatest;
  loadAjaxDoc(function(chatLog) {
    receivedChatLog(chatLog, callback);
  }, "php/chat.php", "POST", msg);
}

function receivedChatLog(chatLog, callback) {
  let chatEntries = chatLog.children;
  let messages = [];
  for(let i = 0; i < chatEntries.length; i++) {
    let entryData = chatEntries[i].children;
    messages.push({
      "msg": entryData[0].innerHTML,
      "from": entryData[1].innerHTML,
      "time": entryData[2].innerHTML
    });

    if(latestChatMessage != undefined) {
      let msgTime = messages[messages.length-1]["time"];
      if(msgTime == latestChatMessage["time"]) { messages = []; }
    }
  }
  callback(messages);
}

function sendChatMessage(e) {
  e.preventDefault();
  if(chatBox == undefined) return;
  let chatMsg = chatBox.value;
  chatBox.value = "";
  let msg = "msg=" + chatMsg + "&from=" + myId + "&room=" + roomId;
  loadAjaxDoc(null, "php/chat.php", "POST", msg);
}

function fetchMoveHistory(callback) {
  let mode = latestMove == undefined ? 0 : 1;
  let doc = "php/moveHistory.php?room="+roomId+"&mode="+mode;
  loadAjaxDoc(function(moveHistory) {
    receivedMoveHistory(moveHistory, callback);
  }, doc, "GET", null);
}

function receivedMoveHistory(moveHistory, callback) {
  let moveEntries = moveHistory.children;
  let moves = [];
  for(let i = 0; i < moveEntries.length; i++) {
    moves.push(moveEntries[i].innerHTML);
  }
  callback(moves);
}

function fetchTimeStatus(callback) {
  let doc = "php/moveHistory.php?room="+roomId;
  loadAjaxDoc(function(timeStatus) {
    receivedTimeStatus(timeStatus, callback);
  }, doc, "GET", null);
}

function receivedTimeStatus(timeStatus, callback) {
  whiteTime.innerHTML = timeStatus[0].innerHTML%60+":"+timeStatus[0].innerHTML/60;
  blackTime.innerHTML = timeStatus[1].innerHTML%60+":"+timeStatus[1].innerHTML/60;
}

function sendMoveRequest(move) {
  let doc = "php/moveHistory.php?room="+roomId+"&move="+move+"&playerId="+myId;
  loadAjaxDoc(function(response) {
    receivedMoveResponse(response, move);
  }, doc, "GET", null);
}

function receivedMoveResponse(response, move) {
  if(response.innerHTML == "true") {
    isMyTurn = false;
    myLatestMove = move;
  }
}

function sendCheckMate() {
  let doc = "php/moveHistory.php?room="+roomId+"&checkmate=1&playerId="+myId;
  loadAjaxDoc(function(response) {}, doc, "GET", null);
}
