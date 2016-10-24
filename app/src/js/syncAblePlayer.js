window.Cookies = require( 'js-cookie' );

var icon = require( './icon' );

var syncAblePlayer = function(){
  $( 'body' ).removeClass( 'hasAblePlayer' );
  $( 'video' ).each(function (index, element) {
    $( 'body' ).addClass( 'hasAblePlayer' );
    if ($(element).data('able-player') !== undefined) {
      window.AP = new AblePlayer( $(this), $(element) );

      // disable closed captioning

      var tryClick = setInterval( function(){
        if( typeof AP.initializing === 'undefined' ) return;
        var captions = $( '.able-wrapper .icon-captions' );
        var isClicked = false;
        captions.on( 'click', function(){
          clearInterval( tryClick );
          isClicked = true;
        } );
        if( !isClicked ) captions.trigger('click');
      }, 200 );

      var inViewport = 0;  // 0 for in viewport, 1 for below, -1 for above
      JUMPTOACTIVE = setInterval( function(){
        var current = $( '.able-highlight' );
        if( !current.length ) return;

        var currentPos = {
          top: current.offset().top,
          bottom: current.offset().top + current.height()
        }

        var windowPos = {
          top: $( window ).scrollTop(),
          bottom: $( window ).scrollTop() + $( window ).height()
        }

        if( currentPos.top > windowPos.bottom ){
          var _inViewport = 1;
        } else if( currentPos.bottom < windowPos.top ){
          var _inViewport = -1;
        } else {
          var _inViewport = 0;
          $( '.transcript-jumpToActive' ).remove();
        }

        if( _inViewport !== inViewport && _inViewport !== 0 ){
          $( '.transcript-jumpToActive' ).remove();
          var jumpToActive = $( '<button data-action="jumpToActive" class="transcript-jumpToActive">'+icon( ( _inViewport === 1 ? 'down' : 'up' ), 'jump' ) +'Jump to active section</button>' );
          if( _inViewport === -1 ){
            jumpToActive.css( {
              top: $(window).width() <= 568 ? 0 : $( '.contentHeaderOuter' ).outerHeight(),
              bottom: 'auto'
            } );
          }
          $( 'body' ).append( jumpToActive );
        }
        inViewport = _inViewport;

      }, 1000 );
    }

    $( 'body' ).on( 'click', '[data-action="jumpToActive"]', function(){
      $(this).hide();
      $( 'body,html' ).animate( {
        scrollTop: (function(){
          var offset = $( '.contentHeaderOuter' ).outerHeight() + 32;
          return $( '.able-highlight' ).offset().top - offset;
        } )()
      }, TRANSITIONTIME );
    } );

  } );

  // do we have a timestamp that matches a hash? if so return it
  var getNodeFromTimestamp = function(){
    if( window.location.hash ){
      var hash = window.location.hash;
      var match_id = hash.match(/\#(\d*)/);
      if( match_id && match_id[1].length ){
        if( $( '[data-start="'+match_id[1]+'"]' ).length ){
          return $( '[data-start="'+match_id[1]+'"]' );  
        }
      }
    }
    return false;
  }

  if( getNodeFromTimestamp() ){
    var timestamp = getNodeFromTimestamp();
    var offset = $( '.contentHeaderOuter' ).outerHeight() + 32;
    $( 'body, html' ).scrollTop( timestamp.offset().top - offset );
    timestamp.addClass( 'able-highlight' );
    var tryClick = setInterval( function(){
      if( typeof AP.initializing === 'undefined' ) return;
      timestamp.trigger('click');
      timestamp.on( 'click', function(){
        clearInterval( tryClick );
      } );
    }, 200 );
  }

  $( '.able-wrapper' ).addClass( 'able-wrapper--loaded' );

}

module.exports = syncAblePlayer;
