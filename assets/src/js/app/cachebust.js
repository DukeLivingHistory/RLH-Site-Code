var cachebust = function(hasArgs){
  return (hasArgs ? '&' : '?') + 'c=' + Date.now();
}

module.exports = cachebust;
