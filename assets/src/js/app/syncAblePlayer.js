window.Cookies = require('js-cookie')
const cachebust  = require('./cachebust')
const icon       = require('./icon')
const getNodeFromTimestamp = require('./getNodeFromTimestamp')

const syncAblePlayer = function(transcript, id, supp){
  $('body').removeClass('hasAblePlayer')
  $('video').each(function (index, element) {
    $('body').addClass('hasAblePlayer')
    if ($(element).data('able-player') !== undefined) {
      window.AP = new AblePlayer($(this), $(element))

      // transform transcript into useable format
      const sections = transcript.filter(node => node.type === 'section_break')

      const headings = sections.filter(node => !node.note_chapter).map(node => {
        return {
          text: node.contents,
          start: node.start
        }
      })

      const chapters = sections.filter(node => node.note_chapter).map(node => {
        return {
          text: node.contents,
          start: node.start
        }
      })

      const body = transcript.filter(node => (
        node.type !== 'paragraph_break' &&
        node.type !== 'description'
      )).map(node => ({
        text: node.contents,
        start: node.start
      }))

      const descriptions = transcript.filter(node => node.type === 'description')
        .map(node => ({
          text: node.contents,
          start: node.start,
        }))

      const suppContent = Object.entries(supp.timestamps).map(node => {
        const values = ['content', 'blockquote', 'attribution', 'title', 'description', 'link_text']
        const pieces = node[1]
        return {
          text: pieces.reduce((all, piece) => {
            return all + values.reduce((acc, value) => {
              if(piece.data[value]){
                acc += piece.data[value]
              }
              return acc
            }, '')
          }, ''),
          start: parseInt(node[0])
        }
      })

      // hacky way to wait until youtube iframe is initialized before running add dot code
      const tryYouTube = setInterval(() => {
        const youTubePlayer = AP.youTubePlayer
        if(youTubePlayer && youTubePlayer.getDuration && !!youTubePlayer.getDuration()){
          const duration = youTubePlayer.getDuration()

          ableplayerAddDots(AP, headings, {
            duration,
            format:  'array',
            color:   window.HEADINGOPTS.COLOR   || '#fff',
            width:   window.HEADINGOPTS.WIDTH   || 1,
            height:  window.HEADINGOPTS.HEIGHT  || false,
            display: window.HEADINGOPTS.DISPLAY || 'line',
          })
          .then(player => {
            clearInterval(tryYouTube)
            ableplayerAddDots(player, chapters, {
              duration,
              format: 'array',
              color:   window.CHAPTEROPTS.COLOR   || '#fff',
              width:   window.CHAPTEROPTS.WIDTH   || 1,
              height:  window.CHAPTEROPTS.HEIGHT  || false,
              display: window.CHAPTEROPTS.DISPLAY || 'line',
            })
            .then(player => {
              ableplayerSearch(player, '#video-search', body, {
                duration,
                color:   window.SEARCHOPTS.COLOR   || '#fff',
                width:   window.SEARCHOPTS.WIDTH   || 1,
                height:  window.SEARCHOPTS.HEIGHT  || false,
                display: window.SEARCHOPTS.DISPLAY || 'line',
              })
              .then(player => {
                ableplayerSearch(player, '#video-search', suppContent, {
                  duration,
                  color:   window.SUPP_CONT_OPTS.COLOR   || '#fff',
                  width:   window.SUPP_CONT_OPTS.WIDTH   || 1,
                  height:  window.SUPP_CONT_OPTS.HEIGHT  || false,
                  display: window.SUPP_CONT_OPTS.DISPLAY || 'line',
                })
                .then(player => {
                  ableplayerSearch(player, '#video-search', descriptions, {
                    duration,
                    color:   window.AUDIOOPTS.COLOR   || '#fff',
                    width:   window.AUDIOOPTS.WIDTH   || 1,
                    height:  window.AUDIOOPTS.HEIGHT  || false,
                    display: window.AUDIOOPTS.DISPLAY || 'line',
                  })
                  .then(player => {
                    // Hack to trigger search
                    $('#video-search').val(window.SEARCHTERM).trigger('keyup')
                  }).catch(err => console.log(err))
                }).catch(err => console.log(err))
              }).catch(err => console.log(err))
            }).catch(err => console.log(err))
          }).catch(err => console.log(err))

        }
      }, 200)

      // disable closed captioning
      var tryClick = setInterval(function(){
        if(typeof AP.initializing === 'undefined') return
        var captions = $('.able-wrapper .icon-captions')
        var isClicked = false
        captions.on('click', function(){
          clearInterval(tryClick)
          isClicked = true
        })
        if(!isClicked) captions.trigger('click')
      }, 200)

      var inViewport = 0 // 0 for in viewport, 1 for below, -1 for above
      JUMPTOACTIVE = setInterval(function(){
        var current = $('.able-highlight')
        if(!current.length) return

        var currentPos = {
          top: current.offset().top,
          bottom: current.offset().top + current.height()
        }

        var windowPos = {
          top: $(window).scrollTop(),
          bottom: $(window).scrollTop() + $(window).height()
        }

        if(currentPos.top > windowPos.bottom){
          var _inViewport = 1
        } else if(currentPos.bottom < windowPos.top){
          var _inViewport = -1
        } else {
          var _inViewport = 0
          $('.transcript-jumpToActive').remove()
        }

        if(_inViewport !== inViewport && _inViewport !== 0){
          $('.transcript-jumpToActive').remove()
          var jumpToActive = $('<button data-action="jumpToActive" class="transcript-jumpToActive">'+icon((_inViewport === 1 ? 'down' : 'up'), 'jump') +'Jump to active section</button>')
          if(_inViewport === -1){
            jumpToActive.css({
              top: $(window).width() <= 568 ? 0 : $('.contentHeaderOuter').outerHeight(),
              bottom: 'auto'
            })
          }
          $('body').append(jumpToActive)
        }
        inViewport = _inViewport

      }, 1000)
    }

    $('body').on('click', '[data-action="jumpToActive"]', function(){
      $(this).hide()
      $('body,html').animate({
        scrollTop: (function(){
          const offset = $('.contentHeaderOuter').outerHeight() + 32
          return $('.able-highlight').offset().top - offset
        })()
      }, TRANSITIONTIME)
    })

  })

  if(getNodeFromTimestamp()){
    const timestamp = getNodeFromTimestamp()
    const offset = $('.contentHeaderOuter').outerHeight() + 32
    setTimeout(() => {
      $('body, html').scrollTop(timestamp.offset().top - offset)
      timestamp.addClass('able-highlight')

      // Hack to trigger play
      const tryClick = setInterval(function(){
        if(typeof AP.initializing === 'undefined') return
        timestamp.trigger('click')
        timestamp.on('click', function(){
          clearInterval(tryClick)
        })
      }, 200)
    }, 300)
  }

  $('.able-wrapper').addClass('able-wrapper--loaded')

}

module.exports = syncAblePlayer
