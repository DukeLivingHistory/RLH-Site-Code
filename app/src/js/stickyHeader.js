var stickyHeader = function( page, elem, target ){

  const bottom =  $(target).offset().top + $(target).height()
  const height = $(elem).height();
  let unsticky = true; // toggled on/off and checked before manipulating DOM
  const hasVideo = $( 'video' ).length;
  let oldTop = 0 // cache value so we can tell if we scrolled up/down

  const handleOn = function(){
    $(elem).addClass( elem.slice(1) + '--sticky' );
    page.css( 'padding-top', height );
    unsticky = false;
    setTimeout( function(){
      if( typeof AP !== 'undefined' ) AP.refreshControls();
    }, 500 ); //offset by css transition time
  }

  const handleOff = function(){
    $(elem).removeClass(elem.slice(1) + '--sticky');
    page.css( 'padding-top', '' );
    unsticky = true;
    setTimeout( function(){
      if( typeof AP !== 'undefined' ) AP.refreshControls();
    }, 500 ); //offset by css transition time
  }

  const handler = function(){
    // if( $(window).width() <= 568 || !hasVideo ){
    if(!hasVideo){
      handleOff()
      return
    }
    var top = $(window).scrollTop()
    if( top > bottom && unsticky ){
      handleOn()
    } else if( top < bottom && !unsticky ) {
      handleOff()
    }

    if(top < oldTop) {
      console.log('up')
      $(elem).addClass( elem.slice(1) + '--justScrolledUp' );
    } else {
      console.log('down')
      $(elem).removeClass( elem.slice(1) + '--justScrolledUp' );
    }

    oldTop = top
  }

  $(window).on( 'scroll resize orientationchange', handler );

  $(elem).find( '[data-action="top"]').click( function(){
    $( 'body, html' ).animate( {
      scrollTop: 0
    }, TRANSITIONTIME );
  });

  $(elem).find( '[data-action="toggle"] select').change( function(){
    var size = $(this).val().toLowerCase();
    var selector = $(this).parent().attr( 'data-target' )
    var target = $( selector );
    target.removeClass();
    target.addClass( selector.slice(1) + ' ' + selector.slice(1) + '--' + size );
    setTimeout( function(){
      if( typeof AP !== 'undefined' ) AP.refreshControls();
    }, 500 ); //offset by css transition time
  } );

};

module.exports = stickyHeader;
