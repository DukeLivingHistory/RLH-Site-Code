var animatePage = function( wrapper, page, dir, cb ){
  var callback = cb || false;

  wrapper.css( 'min-height', '100vh' );
  wrapper.append( page );

  if( dir && !IGNOREDIR ){
    page.addClass( 'pageTrans pageTrans--'+dir );
    setTimeout( function(){
      page.removeClass( 'pageTrans--'+dir );
    }, 20 );
    setTimeout( function(){
      page.removeClass( 'pageTrans' );
      if( callback ) callback();
    }, TRANSITIONTIME )
  } else {
    if( callback ) callback();
  }

  wrapper.css( 'min-height' );

}

module.exports = animatePage;
