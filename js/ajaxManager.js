var xmlHttp = new XMLHttpRequest();

function fetchMoveHistory(onlyLatest, callback) {
  if(xmlHttp.readyState == 0 || xmlHttp.readyState == 4) {
    xmlHttp.onreadystatechange = function() {
      receivedBoardState(firstTime, callback);
    }
    xmlHttp.open("GET", "php/moveHistory.php?room="+roomId+"&onlyLatest="+onlyLatest, true);
    xmlHttp.send(null);
  }
}

function receivedMoveHistory(onlyLatest, callback) {
  if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
    let xmlResponse = xmlHttp.responseXML;
    if(xmlResponse) {
      let responseElement = xmlResponse.documentElement;
      console.log(responseElement);
      if(onlyLatest) {}
      else { loadMoveHistory(); }
      // TODO: Assign local board state
    }
    else { setTimeout('fetchMoveHistory()', 1000); }
  }
}
