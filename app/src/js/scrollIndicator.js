var icon = require( './icon' );

var scrollIndicator = {

  scrollIndicator: $( '<div class="scrollIndicator">'+icon( 'down', 'scrollIndicator' )+'</div>' ),

  remove: function(){
    this.scrollIndicator.remove();
  },

  add: function( ref ){
    this.toggle( ref );
    $( window ).on( 'scroll resize', function(){
      this.toggle( ref );
    }.bind(this) );

    this.scrollIndicator.click( function(){
      $( 'body,html' ).animate( {
        scrollTop: (function(){
          var offset = 110;
          return $( ref ).offset().top - offset;
        })()
      }, 500 );
    } );

    return this.scrollIndicator;
  },

  toggle: function( elem ){
    if( !$(elem).length ) return;
    // 33 = padding-top of transcript
    if( $( window ).scrollTop() + $( window ).height() > $( elem ).offset().top + 33 ){
      this.scrollIndicator.addClass( 'scrollIndicator--hidden' );
    } else {
      this.scrollIndicator.removeClass( 'scrollIndicator--hidden' );
    }
  }


}

module.exports = scrollIndicator;
