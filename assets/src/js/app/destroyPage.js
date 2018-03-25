var destroyPage = function( wrapper, dir ){
  var prev = wrapper.find( '.page:eq(0)' );
  if( !IGNOREDIR ){
    prev.css( {
      'position': 'absolute',
      'top '  : wrapper.offset().top,
      'left'  : wrapper.offset().left,
      'width' : wrapper.width()
    } );
    prev.addClass( 'pageTrans pageTrans--'+dir );
    wrapper.css( 'min-height', wrapper.height() );
    setTimeout( function(){
      prev.remove();
    }, TRANSITIONTIME );
  } else {
    prev.remove();
  }
}

module.exports = destroyPage;
