var buildTranscript = function( wrapper, id, cb ){

  var outer = $( '<section class="able-transcript-area transcript" id="transcript-'+id+'">' );
  var transcript = $( '<div class="able-transcript" />' );
  var callback = cb || false;
  var html = '';

  $.get( '/wp-json/v1/interviews/'+id+'/transcript?return=transcript_contents', function( data ){

    if( !data ){
      if( callback ) callback( data );
      return;
    }

    var paragraphInit = true;
    var paragraphOpen = false;
    var jumptoInit = false;
    var jumpto = $( '#select-'+id );
    $( data ).each(function(){
      switch( this.type ){
        case 'paragraph_break':
          html += paragraphInit ? '<div class="able-transcript-block">' : '</div>';
          html += paragraphOpen ? '<div class="able-transcript-block">' : '';
          paragraphInit = false;
          paragraphOpen = !paragraphOpen;
          break;
        case 'section_break':
          html += paragraphOpen ? '</div>' : '';
          html += '<div data-highlight="transcript" class="transcript-section able-unspoken" data-timestamp="'+this.timestamp+'">'+this.text+'</div>';
          jumpto.append( '<option value="'+this.timestamp+'">'+this.text+'</option>' );
          if( !jumptoInit ){
            jumptoInit = true;
            jumpto.parent().show();
            jumpto.on( 'change', function(){
                var val = $(this).val();
                var offset = 0;
                if( val === 'default' ){
                  $('body,html').animate( {
                    scrollTop: 0
                  }, TRANSITIONTIME*2 );
                  return;
                }
                var offset = ( $(window).width() >= 568 ) ? $( '.contentHeaderOuter' ).height() + 16 : 0;
                $('body,html').animate( {
                  scrollTop: $('.transcript-section[data-timestamp="'+val+'"]').offset().top - offset
                }, TRANSITIONTIME );
                setTimeout( function(){
                  offset = offset - jumpto.height();
                  $('body,html').animate( {
                    scrollTop: $('.transcript-section[data-timestamp="'+val+'"]').offset().top - offset
                  }, TRANSITIONTIME/2 );
                }, TRANSITIONTIME )
            } );
          }
          break;
        case 'speaker_break':
          html += paragraphOpen ? '</div>' : '';
          html += '<div data-highlight="next" class="transcript-speaker able-unspoken">'+this.text+'</div>';
          break;
        case 'transcript_node':
          html += '<span tabindex="0" class="able-transcript-seekpoint able-transcript-caption transcript-node" data-highlight="transcript" data-start="'+this.start+'" data-end="'+this.end+'">'+this.text+ '</span> ';
          break;
      }
    } );
    jumpto.append( '<option value="default">Back to top</option>' );
    transcript.append( html );
    outer.append( transcript );
    outer.append( '<div class="able-window-toolbar" />' );
    wrapper.append( outer );
    if( callback ) callback( data );
  } );
}

module.exports = buildTranscript;
