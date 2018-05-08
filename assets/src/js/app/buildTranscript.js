var cachebust             = require('./cachebust');
var buildTranscriptMarkup = require('./buildTranscriptMarkup');
var highlightTranscript   = require('./highlightTranscript');
var highlightSuppCont     = require('./highlightSuppCont');
var Cookies               = require('js-cookie');

var buildTranscript = function( page, id, cb ){
  var wrapper = $('<div class="transcript-instructions-wrap">')
  var outer = $( '<section class="able-transcript-area transcript" id="transcript-'+id+'">' );
  var transcript = $( '<div id="transcript-inner" class="able-transcript" />' );
  var callback = cb || false;

  let getUseDescription = (init) => {
    if(init){
      const cookies = Cookies.get('Able-Player')
      if(!cookies) return false

      const json = JSON.parse(cookies)
      return json.preferences && json.preferences.prefDesc
    } else {
      return !$('.able-button-handler-descriptions').hasClass('buttonOff')
    }
  }

  let getNodes = () => null

  // Window scoped variables for quick fix
  window.JUMPTOINIT = false
  const onEachNode = (node) => {
    window.JUMPTO = $('#select-'+id)
    if(node.type === 'section_break'){
      JUMPTO.append(`<option value="${node.start}">${node.contents}</option>`)
      if(!JUMPTOINIT){
        window.JUMPTOINIT = true
        JUMPTO.parent().show()
        JUMPTO.on( 'change', function(){
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
        });
      }
    }
  }

  const url = '/wp-json/v1/interviews/'+id+'/transcript?return=transcript_contents'+cachebust(true)

  $.get(url, function( data ){

    getNodes = () => data

    if( !data ){
      if( callback ) callback( data );
      return;
    }


    const html = buildTranscriptMarkup(data, {
      onEach: onEachNode,
      useDescription: getUseDescription(true)
    })

    JUMPTO.append( '<option value="default">Back to top</option>' );
    transcript.append( html );
    outer.append( transcript );
    outer.append( '<div class="able-window-toolbar" />' );
    wrapper.append( '<div class="transcript-instructions">'+window.INSTRUCTIONS+'</div>' );
    wrapper.append( outer );
    page.append(wrapper)
    if( callback ) callback( data, wrapper );
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
  let initDebuff = false
  $('body').on('keyup', '#video-search', function(){
    window.SEARCHDEBUFF = setTimeout(() => {
      const value = $(this).val()
      const keyword = (value.length > 2) ? value : false
      highlightTranscript(transcript, '[data-node]', keyword)
      highlightSuppCont('.suppCont-single', '[data-suppcont]', keyword)
    }, 500)
  })
}

module.exports = buildTranscript;
