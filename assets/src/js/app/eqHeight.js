var eqHeight = function( target, cb ){
  var height = 0;
  var callback = cb || false;
  var eq = function(){
    height = 0;
    $(target).css( 'height', '' );
    $(target).each( function(){
      var _height = $(this).height();

      if( _height > height ) height = _height;

      if( callback ) callback();
    } );

    $( target ).each( function(){
      $(this).css( 'height', height );
    } )
  };

  eq();
  $( window ).resize( eq );

}

module.exports = eqHeight;
