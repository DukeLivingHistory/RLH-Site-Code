var internalLink = require('./internalLink');

var buildCollectionsList = function( collections ){
  var html = '<p class="contentHeader-collectionsList">Part of the <strong> ';
  html +=      internalLink( collections[0], collections[0].link_text );
  if( collections.length > 2 ){
    for( var i = 1, x = collections.length - 1; i < x; i++ ){
      html +=       ', ' + internalLink( collections[i], collections[i].link_text );;
    }
  }
  if( collections.length > 1 ){
    if( collections.length > 2) html += ', '; //oxford comma
    html +=     ' <span class="contentheader-collectionsAnd">and</span> ';
    html +=     internalLink( collections[collections.length - 1], collections[collections.length - 1].link_text );
  }
  html +=     '</strong> collection'
  if( collections.length > 1 ){
    html +=     's';
  }
  html +=     '</p>';
  return html;
}

module.exports = buildCollectionsList;
