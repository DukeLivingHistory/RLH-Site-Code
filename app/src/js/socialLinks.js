var fb = require('facebook-share-link');
var Clipboard = require('clipboard');
var icon = require('./icon');

var socialLinks = function( url, title, excerpt, extraClipBoardText ){
  var url = url || window.location.href;
  var clipBoardText = extraClipBoardText ? extraClipBoardText.trim() + '\n' + url + '\n' : title + ' ' + url;
  var linkList = $( '<ul class="social social--inline" data-url="'+url+'" data-excerpt="'+excerpt+'" data-title="'+title+'" />' );
  var facebook = $( '<li tabindex="0" data-soc="fb"><span>Share on Facebook</span>'+icon( 'facebook', 'social')+'</li>' );
  var twitter = $( '<li tabindex="0" data-soc="tw"><span>Share on Twitter</span>'+icon( 'twitter', 'social')+'</li>' );
  var link = $( '<li tabindex="0" readonly="readonly" data-soc="link" data-clipboard-text="'+clipBoardText+'"><span class="inner" style="position:static;"><span>Share URL</span>'+icon( 'link', 'social')+'</span></li>' );

  var response = function( error, isHighlight ){
    var message = '';
    if( error ){
      message = 'Your browser doesn\'t support direct copying.'
    } else if( isHighlight ) {
      message = "Selected text plus link copied to clipboard!"
    } else {
      message = "Link copied to clipboard!"
    }
    var className = 'socialCopy ';
    className += error ? 'socialCopy--error' : 'socialCopy--success';
    return '<div class="'+className+'">'+message+'</div>';
  }

  linkList.append( facebook );
  linkList.append( twitter );
  linkList.append( link );

  if( !window.SOCIALINIT ){
    window.SOCIALINIT = true;
    var getQuote = function( elem ){
      var _excerpt = $(elem).parent().attr( 'data-excerpt' );
      var _title = $(elem).parent().attr( 'data-title' );
      return window.HIGHLIGHTED || _title || _excerpt || window.DESCRIPTION;
    }
    var share = fb( window.FB_APP_ID );
    var quote = getQuote();
    var clipboard = new Clipboard( '[data-soc="link"]' );
    var clipboardHL = new Clipboard( '.socialPopup [data-soc="link"]' );
    quote = encodeURIComponent( quote );

    $( 'body' ).on( 'keyup', function(){
      var link = $( '[data-soc="link"]' );
      if( !link ) return;
      var selection = document.getSelection();
      var text = selection.toString();
      if( text.length ) window.HIGHLIGHTED = text;

      var anchor = $( selection.anchorNode.parentNode ); // where drag started
      var focus = $( selection.focusNode.parentNode ); // where drag ended
      var first = anchor.index() < focus.index() ? anchor : focus; // which comes first in DOM
      var _url;

      if( anchor.attr( 'data-highlight') ){ //check for special cases
        if( first.attr( 'data-highlight') === 'next' ){
          var next = first.next();
          var timestamp =  next.attr( 'data-start' ) || next.attr( 'data-timestamp' );
          _url = url + '#'+timestamp;
        } else if( anchor.attr( 'data-highlight') === 'transcript' ){
          var timestamp =  first.attr( 'data-start' ) || first.attr( 'data-timestamp' );
          _url = url + '#'+timestamp;
        }
      }

      link.attr( 'data-clipboard-text', ( text ? text + '\n' + _url + '\n' : _url )  );
      clipboard = new Clipboard( '[data-soc="link"]' );
    } );

    $( 'body' ).on( 'click', '[data-soc="fb"]', function(){
      var url = $(this).parent().attr( 'data-url' );
      var quote = getQuote(this);
      var link = share( {
        href: url,
        display: 'popup',
        quote: quote
      } );
      window.open( link, 'fbShareWindow', 'height=450, width=550, top=' + ( $(window).height() / 2 - 275) + ', left=' + ($(window).width() / 2 - 225) + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
    } );

    $( 'body' ).on( 'click', '[data-soc="tw"]', function(){
      var url = encodeURIComponent( $(this).parent().attr( 'data-url' ) );
      var quote = encodeURIComponent( getQuote(this) );
      var link = 'https://twitter.com/intent/tweet?url='+url+'&text='+quote;
      window.open( link, 'twShareWindow', 'height=450, width=550, top=' + ( $(window).height() / 2 - 275) + ', left=' + ($(window).width() / 2 - 225) + ', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
    } );

    var wasClipboardSuccessful = false;

    clipboard.on( 'success', function(){
      console.log( 'cb s' );
      wasClipboardSuccessful = true;
      $('body').append( response() );
      $( '.socialCopy' ).css( {
        position: 'absolute',
        right: '1em',
        bottom: 'auto',
        top: function(){
          var bottom = $(window).scrollTop() + $(window).height() - 16 - $(this).height() +'px';
          return bottom;
        }
      } );
      setTimeout( function(){
        $( '.socialCopy' ).remove();
      }, 2000 );
    } );

    clipboardHL.on( 'success', function(){
      console.log( 'cbhl s' );
      wasClipboardSuccessful = true;
      $('body').append( response( false, true ) );
      $( '.socialCopy' ).css( {
        position: 'absolute',
        right: '1em',
        bottom: 'auto',
        top: function(){
          var bottom = $(window).scrollTop() + $(window).height() - 16 - $(this).height() +'px';
          return bottom;
        }
      } );
      setTimeout( function(){
        $( '.socialCopy' ).remove();
      }, 2000 );
    } );

    $( 'body' ).on( 'click', '[data-soc="link"]', function(e){
      e.preventDefault();
      setTimeout( function(){
        if( !wasClipboardSuccessful ){
          handleClipboardError();
        }
        wasClipboardSuccessful = false;
      }, 250 );
    } );

    var handleClipboardError = function(){
      var _url = $('body > textarea').val(); // text value
      $( 'body > textarea' ).remove(); // iOS thing
      $.featherlight( {
        html:  '<div class="featherlight-inner"><div class="featherlight-textarea"><p>Copy the link and text below.</p><textarea>'+_url+'</textarea></div></div>',
        afterContent: function(){
          $( 'body' ).css( 'overflow', 'hidden' );
          $( '.featherlight-close').addClass( 'featherlight-close--textarea' );
          $( '.featherlight-inner' ).append( response(true) );
          $( '.socialCopy' ).css( {
            position: 'absolute',
            right: '1em',
            bottom: 'auto',
            top: function(){
              var bottom = $(window).scrollTop() + $(window).height() - 16 - $(this).height() +'px';
              return bottom;
            }
          } );
          var input = $( '.featherlight-inner' ).find( 'textarea' );

          input.each( function(){
            this.style.height = this.scrollHeight+'px';
            this.selectionStart = 0;
            this.selectionEnd = 999;
          } );

        },
        afterClose: function(){
          //restore body scrolling
          $( 'body' ).css( 'overflow', '' );
        }
      } );
      setTimeout( function(){
        $( '.socialCopy' ).remove();
      }, 2000 );
    }

    clipboard.on( 'error', handleClipboardError );

  }

  return linkList[0].outerHTML;

}

module.exports = socialLinks
