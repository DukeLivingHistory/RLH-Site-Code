var eqHeight = require('./eqHeight');
var img = require('./img');

$( document ).ready( function(){

  var initEqHeight = (function(){
    if( $(window).width() < 768 ) return;
    // eqHeight( '.js-eqHeight--featured', function(){
    //   $( '.js-eqHeight--featured' ).addClass( 'post--loaded' );
    // } );
    eqHeight( '.js-eqHeight--roll', function(){
      $( '.js-eqHeight--roll' ).addClass( 'post--loaded' );
    } );
    eqHeight( '.js-eqHeight--bucket'  );
  })();

  $(window).resize( initEqHeight );

  var toggleNav = function( target ){
    $( target ).toggleClass( target.slice(1) + '--expand' );
  }

  $( '.header-navToggle' ).click( function(){
    toggleNav( '.header-navInner' );
    toggleNav( '.header-navToggleButton' );
    var label = $( '.header-navToggleLabel' );
    label.text( label.text() === 'Menu' ? 'Close' : 'Menu' );
  } );

  $( '.researchMenu-toggle' ).click( function(){
    toggleNav( '.researchMenu' );
  } );

  img( '.js-img' );

} );
