var respImg = require( './respImg' );
var icon = require( './icon' );

//vertically align supp cont with transcript and set appropriate handlers
var syncTimestamps = function( supp, node, transcript ){

  var expand = function( target ){
    if( $(window).width() > 568 ){ // from bootstrap
      $( '[data-action="expand"]' ).removeClass( 'expand' )
        .find( '[data-action="close"] use' ).attr( 'xlink:href', '#expand' );
      target.addClass( 'expand' );
      target.find( '[data-action="close"] use' ).attr( 'xlink:href', '#contract');
      var img = target.find( '.respImg-defer' );
      respImg.load( img );
    } else {
      var innerContent = $( target ).find( '.suppCont-content' ).html();
      var type = $( target ).find( '.icon' )[0].outerHTML;
      $.featherlight( {
        html: '<div class="suppCont-lightbox"><div class="suppCont-content">'+type+innerContent+'</div></div>',
        afterContent: function(){
          $( 'body' ).css( 'overflow', 'hidden' );
          var img = $( '.featherlight-content' ).find( '.respImg-defer' );
          respImg.load( img );
          $( '.featherlight-close-icon' ).html( icon( 'contract', 'suppContent-lightboxClose' )  );
        },
        afterClose: function(){
          //restore body scrolling
          $( 'body' ).css( 'overflow', '' );
        }
      } );
    }
  }

  var expandMultiple = function(target){
    if($(window).width() <= 568) return;
    target.addClass('expand');
    target.find( '[data-action="close"] use' ).attr( 'xlink:href', '#contract');
    var img = target.find('.respImg-defer');
    respImg.load(img);
  }

  expandMultiple($('[data-opendefault="true"]'));

  var position = function(){
    $(supp).each( function(){

      // assign match data attribute if not already set
      $(this).data( 'match', $(this).data('match') || (function(){
        return $( node+'[data-start="' + $(this).attr( 'data-timestamp' ) + '"]' )
      }.bind(this))() );

      var match = $(this).data( 'match' );
      if( !match.length ) return;
      var matchPos = $(match).offset().top;
      var transcriptPos = $(transcript).offset().top
      var _this = this;

      // compare top of match to bottom of any previous suppcont
      // this refers to element being looped over
      // _this refers to element being positioned
      $(supp).each( function(i){
        var bottom = Math.floor( $(this).offset().top - transcriptPos );
        var _top = Math.floor( matchPos - transcriptPos );
        if( i === 0 ){
          $(_this).css( {
            position: 'absolute',
            top: _top,
            left: 0,
            right: 0
          } );
          return false;
        }
        $(_this).css( {
          position: 'absolute',
          top: _top > bottom + $(this).height() ? _top : bottom,
          left: 0,
          right: 0
        } );
        return _top > bottom; // if false, stop iterating bc we've found a match
      } );

      $(this).on( 'mouseenter', function(){
        $(match).addClass( node.slice(1)+'--suppHover' );
      } ).on( 'mouseleave', function(){
        $(match).removeClass( node.slice(1)+'--suppHover' );
      } );

      $(match).on( 'mouseenter', function(){
        $(this).addClass( supp.slice(1)+'--suppHover' );
      }.bind(this) ).on( 'mouseleave', function(){
        $(this).removeClass( supp.slice(1)+'--suppHover' );
      }.bind(this) );

      var shouldAdvance = false;
      $(match).blur( function(){
        var next = $(match).next();
        var item = $(this).find( 'li:eq(0)' )
        var lastChild = item.find('li:last-of-type');
        shouldAdvance = true;
        item.focus();
        lastChild.blur( function(){
          if( shouldAdvance ){
            next.focus();
          }
          shouldAdvance = false;
        } );
      }.bind(this) )

    } );
  };

  position();
  $( window ).resize( position );

  if( window.location.hash ){
    var hash = window.location.hash;
    var match_id = hash.match(/\#sc\-(\d*)/);
    if( match_id ){
      var match = $( '[data-supp="'+match_id[1]+'"]' );
      expand( match );
      match.parent().data('match').addClass( 'able-highlight' );
      var offset = $( '.contentHeaderOuter' ).height() + 16;
      $( 'body, html' ).scrollTop( match.offset().top - offset );
    }
  }

  $('body').on( 'click', '.expand [data-action="close"]', function(e){
    e.stopPropagation();
    $(this).closest( '[data-action="expand"] ').removeClass( 'expand' );
    $(this).find( 'use' ).attr( 'xlink:href', '#expand' );
  } );

  $('body').on( 'click', '.expand [data-action="close-type"]', function(e){
    e.stopPropagation();
    $(this).closest( '[data-action="expand"] ').removeClass( 'expand' );
  } );

  $( 'body' ).on( 'click', '[data-action="expand"]', function(){
    expand( $(this) );
  } );

}

module.exports = syncTimestamps;
