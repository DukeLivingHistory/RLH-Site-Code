var cachebust             = require('./cachebust');
var buildTranscriptMarkup = require('./buildTranscriptMarkup');
var highlightTranscript   = require('./highlightTranscript');
var Cookies               = require('js-cookie');

var buildTranscript = function( wrapper, id, cb ){

  var outer = $( '<section class="able-transcript-area transcript" id="transcript-'+id+'">' );
  var transcript = $( '<div id="transcript-inner" class="able-transcript" />' );
  var callback = cb || false;

  let jumptoInit = false;
  let jumpto = $( '#select-'+id );

  let getUseDescription = (init) => {
    if(init){
      const cookies = JSON.parse(Cookies.get('Able-Player'))
      return cookies && cookies.preferences.prefDesc
    } else {
      return !$('.able-button-handler-descriptions').hasClass('buttonOff')
    }
  }

  let getNodes = () => null

  const onEachNode = (node) => {
    if(node.type === 'section_break'){
      jumpto.append(`<option value="${node.start}">${node.contents}</option>`);
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
    }
  }

  $.get( '/wp-json/v1/interviews/'+id+'/transcript?return=transcript_contents'+cachebust(true), function( data ){

    getNodes = () => data

    if( !data ){
      if( callback ) callback( data );
      return;
    }


    const html = buildTranscriptMarkup(data, {
      onEach: onEachNode,
      useDescription: getUseDescription(true)
    })

    jumpto.append( '<option value="default">Back to top</option>' );
    transcript.append( html );
    outer.append( transcript );
    outer.append( '<div class="able-window-toolbar" />' );
    wrapper.append( '<div class="transcript-instructions">'+window.INSTRUCTIONS+'</div>' );
    wrapper.append( outer );
    if( callback ) callback( data );
  } );

  // Rebuild on descriptions
  $('body').on('click', '.able-button-handler-descriptions', () =>{
    const html = buildTranscriptMarkup(getNodes(), {
      onEach: onEachNode,
      useDescription: getUseDescription()
    })
    transcript.html(html)
  })

  // Rebuild on search
  $('body').on('keyup', '#video-search', function(){
    window.SEARCHDEBUFF = setTimeout(() => {
      const keyword = $(this).val()
      highlightTranscript(transcript, '[data-node]', keyword)
    }, 500)
  })
}

module.exports = buildTranscript;
