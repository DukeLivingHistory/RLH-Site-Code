var getUrlWithNoHash = function(){
  return window.location.href.split('#')[0];
}

module.exports = getUrlWithNoHash;
