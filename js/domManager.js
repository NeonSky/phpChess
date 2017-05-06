const infoPanel = document.getElementById('infoPanel');
const infoIcon = document.getElementById('infoIcon');
const actionTable = document.getElementById('actionTable');
const chatForm = document.getElementById('chatForm');
const chatBox = document.getElementById('chatBox');
const messageFeed = document.getElementById('messageFeed');

chatForm.addEventListener("submit", sendChatMessage, false);


function appendElement(parent, type, classname) {
  var child = document.createElement(type);
  child.className = classname;
  parent.append(child);
  return child;
}

function updateInfoPanel() {
  console.log("my id: " + myId + ", my color: " + myColor);
  let colorText = 'observer';
  if(myColor) {
    if(myColor == 'l') {
      colorText = "white";
      infoIcon.src = "res/pieces/light-king.svg";
    } else if(myColor == 'd') {
      colorText = "black";
      infoIcon.src = "res/pieces/dark-king.svg";
    }
    infoPanel.innerHTML = "You are player " + colorText;
  } else {
    infoPanel.innerHTML = "You are an observer";
  }
}

function updateActionPanel(moveHistory) {
  let tbody = actionTable.children[0];
  let lastRow = tbody.children[tbody.children.length-1];
  let entriesAdded = 0;

  while(entriesAdded < moveHistory.length) {
    if(lastRow.children.length >= 3) {
      lastRow = appendElement(tbody, 'tr', '');
    } else {
      if(lastRow.children.length == 0) {
        let rowNumber = appendElement(lastRow, 'td');
        rowNumber.innerHTML = (tbody.children.length-1)+".";
      }
      let entry = appendElement(lastRow, 'td');
      entry.innerHTML = moveHistory[entriesAdded].toUpperCase();
      entriesAdded++;
    }
  }
}

function updateChatPanel(chatEntries) {
  for(let i = 0; i < chatEntries.length; i++) {
    let wrapper = appendElement(messageFeed, "div", "msg-wrapper");
    let from = myId == chatEntries[i]['from'] ? "msgFromMe" : "msgFromOther";
    let msg = appendElement(wrapper, "div", "msg " + from);
    msg.innerHTML = chatEntries[i]['msg'];
    latestChatMessage = chatEntries[i];
  }

  if(chatEntries.length > 0) {
    // Scroll down chat view to bottom, to read the new message(s)
    messageFeed.scrollTop = messageFeed.scrollHeight;
  }
}
