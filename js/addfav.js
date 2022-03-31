function addFav() {
  var title = document.title,
    url = document.location,
    UA = navigator.userAgent.toLowerCase(),
    isFF = UA.indexOf('firefox') != -1,
    isMac = UA.indexOf('mac') != -1,
    isWebkit = UA.indexOf('webkit') != -1,
    isIE = UA.indexOf('.net') != -1;
 
  if ((isIE || isFF) && window.external) { // IE, Firefox
    window.external.AddFavorite(url, title);
    return false;
  }
 
  if (isMac || isWebkit) { // Webkit (Chrome, Opera), Mac
    document.getElementById('AddFavViaSheens').innerHTML = 'Нажмите "' + (isMac ? 'Command/Cmd' : 'Ctrl') + ' + D" для добавления страницы в закладки';
    return false;
  }
}