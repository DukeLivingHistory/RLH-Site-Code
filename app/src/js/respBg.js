var cachebust = require('./cachebust');

var respBg = function( elem ){
  var id = elem.attr( 'data-id' );
  var set = elem.attr( 'data-set' );
  var getSize = function(){
    var w = $(window).width();
    if( w >= 1200) return 'lg';
    if( w >= 992 ) return 'md';
    if( w >= 768 ) return 'sm';
    return 'xs';
  };


  $.get( '/wp-json/v1/images/'+id+'/'+set+'_'+getSize()+cachebust(), function( data ){
    elem.css( 'background-image', 'url('+data.requested+')' );
  } );

}

module.exports = respBg;
