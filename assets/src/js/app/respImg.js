window.featherlight = require( './thirdparty/featherlight.min' );
window.featherlightGallery = require( './thirdparty/featherlight.gallery.min.js' );

var cachebust = require('./cachebust');
var internalLink = require( './internalLink' );

var respImg = {

  /*
   * info {
   *  alt
   *  caption
   *  group
   * }
   */
  markup: function( id, size, className, info, hasExtCredit, link ){
    if( info ){
      var alt = info.alt || false;
      var caption = info.caption || false;
      var group = info.group || false;
    }
    var img = '';
    img += '<img src="#" class="'+className+'" ';
    img += 'data-size="'+size+'" ';
    img += 'data-src="'+id+'" ';
    if( alt )     img += 'alt="'+alt+'" ';
    if( caption ) img += 'data-caption="'+caption+'" ';
    if( group )   img += 'data-group="'+group+'" ';
    if( hasExtCredit ) img += 'data-showcredit';
    img += ' />';
    if( link ) return internalLink( link, img );
    return img;
  },

  load: function( className, cb ){
    var callback = cb || false;
    var images = $( className );

    var creditMarkup = function( info, className ){
      var credit = '';
      if( info.author && info.author.length ){
        credit += '<span class="'+className+'">Photo credit: ';
        if( info.src.length ){
          credit += '<a href="'+info.src+'">'
        }
        credit += info.author;
        if( info.src.length ){
          credit += '</a>'
        }
        credit += '</span>';
        return credit;
      } else {
        return '';
      }
    }

    images.each( function( i ){
      var src = $(this).attr( 'data-src' );
      var size = $(this).attr( 'data-size' );
      $.get( '/wp-json/v1/images/'+src+'/'+size+cachebust(), function( data ){

        // data returns an object with four properties:
        // requested = resized img
        // original = full size img
        // caption = img caption
        // creds { name, url }
        var temp = new Image(); // empty img

        temp.onload = function(){ //when empty img has loaded, load img for actual element

          $(this).attr( 'src', data.requested );
          $(this).attr( 'alt', data.alt );
          if( typeof $(this).attr( 'data-showcredit' ) !== 'undefined' ){
            //if( !$( '.respImg-credit' ).length )
            $(this).after( creditMarkup( data.credit, 'respImg-credit' ) );
          }

          // add loaded classes
          if( typeof className === 'string' ){
            $(this).addClass( className.substr(1)+'--loaded');
          } else { // if we're passed a jquery wrapped object
            var selector = className.selector;
            var selectorSplit = selector.split( ' ' );
            selector = selectorSplit[selectorSplit.length - 1 ];
            $(this).addClass( selector.substr(1)+'--loaded');
          }

          // run callback only for last result
          if( images.length - 1 === i && callback ) callback();

        }.bind(this);

        //load img for empty img
        temp.src = data.requested;

        // set lightbox target for full size img
        $(this).attr( 'data-img', data.original );

        // opts for featherlight
        var opts = {
          targetAttr: 'data-img',
          afterContent: function(){
            // can't pass captions through featherlight so we do this instead
            $( '.img-caption' ).remove();
            var lightbox = $( '.featherlight-content' );
            var src = $( '.featherlight-image' ).attr( 'src' );
            var caption = $( '[data-img="'+src+'"]' ).attr( 'data-caption' ) || data.caption || false;
            if( caption ){
              var credit = '';
              if( data.credit.author.length ){
                credit = creditMarkup( data.credit, 'img-credit' );
              }
              lightbox.append( '<div class="img-caption">'+caption+credit+'</div>' );
            }
            //prevent body scrolling
            $( 'body' ).css( 'overflow', 'hidden' );
          },
          afterClose: function(){
            //restore body scrolling
            $( 'body' ).css( 'overflow', '' );
          }
        };

        if( $(this).parent().hasClass('js-internalLink') ){
          return;
        } else if( !$(this).attr( 'data-group' ) ){
          $(this).featherlight( opts );
        } else {
          $( '[data-group="'+$(this).attr( 'data-group' )+'"]' ).featherlightGallery( opts );
        }

      }.bind(this) );
    } );
  }

}

module.exports = respImg;
