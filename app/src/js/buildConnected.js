var icon = require( './icon' );
var internalLink = require( './internalLink' );

var buildConnected = function( related ){
  var list = $( '<ul class="relatedItem-wrapper"/>' );
  for( var i = 0, x = related.length; i < x; i++ ){
    var listItem = $( '<li class="relatedItem relatedItem--'+related[i].type+'" />');
    listItem.append( internalLink( related[i], icon( related[i].type, 'type' )+related[i].name ) );
    list.append( listItem );
  }
  return list;
}

module.exports = buildConnected;
