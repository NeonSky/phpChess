function appendElement(parent, type, classname) {
  var child = document.createElement(type);
  child.className = classname;
  parent.append(child);
  return child;
}
