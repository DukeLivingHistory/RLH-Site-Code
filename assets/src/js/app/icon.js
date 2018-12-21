var icon = function( id, className ){
  var className = className || false;
  // Link Group icon is double width
  if(id === 'link-group') {
    return '<svg class="dbl icon' + ( className ? ' icon--'+className : '' ) +'" viewBox="0 0 256 128"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#'+id+'"></use></svg>';
  }
  if( id === 'condense' || id === 'explode' ){ // handle mis-scaled svgs
    return '<svg class="icon' + ( className ? ' icon--'+className : '' ) +'" viewBox="0 0 72 72"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#'+id+'"></use></svg>';
  }
  return '<svg class="icon' + ( className ? ' icon--'+className : '' ) +'" viewBox="0 0 128 128"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#'+id+'"></use></svg>';
}

module.exports = icon;
