var cachebust = require('./cachebust');
var eqHeight = require( './eqHeight' );
var icon = require( './icon' );
var internalLink = require( './internalLink' );

var buildOtherInCollection = function( page, id, collection ){
  $.get( '/wp-json/v1/collections/'+collection.id+'/?count=3&not='+id+cachebust(true), function(data){
    if ( !data.content.length ) return;
    var others = $( '<div class="others" />' );
    others.append( '<h3 class="others-head">Other interviews and timelines in the <strong>' + data.name + '</strong> collection</h3>' );
    for( var i = 0, x = data.content.length; i < x; i++ ){
      var html = '<article class="others-single others-single--' + data.content[i].type + '">';
      html +=   '<span class="others-singleType">'+icon( data.content[i].type, 'type' ) + ' ' + data.content[i].type + '</span>'
      html +=   '<h4 class="others-singleHead">' + data.content[i].title + '</h4>';
      html +=   '<p class="others-singleDescription">'+data.content[i].excerpt;
      html +=   internalLink( data.content[i], 'View the '+data.content[i].type + ' ' + icon( 'right', 'link' ) )+'</p>';
      html += '</article>';
      others.append( html );
    }
    others.append( '<div class="others-wrap"><div class="others-link">' + internalLink( collection, 'View The Collection' + icon( 'right', 'link' ) ) + '</div></div>' );
    page.append(others);
    eqHeight( '.others-single' );
  } );
}

module.exports = buildOtherInCollection;
