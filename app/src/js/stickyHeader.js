var stickyHeader = function( page, elem, target ){

  var bottom =  $(target).offset().top;
  var height = $(elem).height();
  var unsticky = true; // toggled on/off and checked before manipulating DOM
  var hasVideo = $( 'video' ).length;

  var handleOn = function(){
    $(elem).addClass( elem.slice(1) + '--sticky' );
    page.css( 'padding-top', height );
    unsticky = false;
    setTimeout( function(){
      if( typeof AP !== 'undefined' ) AP.refreshControls();
    }, 500 ); //offset by css transition time
  }

  var handleOff = function(){
    $(elem).removeClass( elem.slice(1) + '--sticky' );
    page.css( 'padding-top', '' );
    unsticky = true;
    setTimeout( function(){
      if( typeof AP !== 'undefined' ) AP.refreshControls();
    }, 500 ); //offset by css transition time
  }

  var handler = function(){
    if( $(window).width() <= 568 || !hasVideo ){
      handleOff();
      return;
    }
    var top = $(window).scrollTop();
    if( top > bottom && unsticky ){
      handleOn();
    } else if( top < bottom && !unsticky ) {
      handleOff();
    }
  }

  $(window).on( 'scroll resize orientationchange', handler );

  $(elem).find( '[data-action="top"]').click( function(){
    $( 'body, html' ).animate( {
      scrollTop: 0
    }, TRANSITIONTIME );
  } );

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
