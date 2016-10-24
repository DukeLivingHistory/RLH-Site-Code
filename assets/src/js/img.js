var img = function( className ){
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
    var image = $(this).attr( 'data-img' );
    if( !image.length ) return;
    $.get( '/wp-json/v1/images/'+image+'/feat_lg', function( data ){

      $(this).attr( 'data-img', data.original );

      if( typeof $(this).attr( 'data-showcredit' ) !== 'undefined' ){
        $(this).append( creditMarkup( data.credit, 'respImg-credit' ) );
      }

      // opts for featherlight
      // var opts = {
      //   targetAttr: 'data-img',
      //   afterContent: function(){
      //     // can't pass captions through featherlight so we do this instead
      //     $( '.img-caption' ).remove();
      //     var lightbox = $( '.featherlight-content' );
      //     var src = $( '.featherlight-image' ).attr( 'src' );
      //     var caption = $( '[data-img="'+src+'"]' ).attr( 'data-caption' ) || data.caption || false;
      //     if( caption ){
      //       var credit = '';
      //       if( data.credit.author.length ){
      //         credit = creditMarkup( data.credit, 'img-credit' );
      //       }
      //       lightbox.append( '<div class="img-caption">'+caption+credit+'</div>' );
      //     }
      //     //prevent body scrolling
      //     $( 'body' ).css( 'overflow', 'hidden' );
      //   },
      //   afterClose: function(){
      //     //restore body scrolling
      //     $( 'body' ).css( 'overflow', '' );
      //   }
      // };
      //
      // $(this).featherlight( opts );

    }.bind(this) );
  } );

}

module.exports = img;
