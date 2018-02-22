var getUrlWithNoHash = require('./getUrlWithNoHash')
var socialLinks = require('./socialLinks')

var socialHighlight = function(target){

  var popup = $('<span class="socialPopup" />')
  var popupOpen = false

  // iOS normalization
  if(window.IS_TOUCH_SCREEN){
    $(document).on('selectionchange', function(){
      // leave popup intact so users can interact with it after dealing with os ui
      var handler = function(){
        popup.show()
        $('body').unbind('touchend', handler)
      }

      $('body').on('touchend', handler)

      // trigger touchend so popup will display
      $(target).trigger('touchend')
    })
  }

  $('body').on('mousedown', function(){
    if(popupOpen){
      popup.hide()
      popupOpen = false
    }
  })

  popup.on('mousedown', function(e){
    e.preventDefault()
    e.stopPropagation()
  })

  $(target).on('mouseup touchend', function(e){
    e.stopPropagation()
    var url = getUrlWithNoHash()
    var selection = document.getSelection()
    var text = selection.toString()
    text = text.replace('"', '&quot')

    if(!selection.anchorNode || !selection.focusNode) return // quick sanity check

    var anchor = $(selection.anchorNode.parentNode) // where drag started
    var focus = $(selection.focusNode.parentNode) // where drag ended
    var first = anchor.index() < focus.index() ? anchor : focus // which comes first in DOM

    if(anchor.attr('data-highlight') || focus.attr('data-highlight')){ //check for special cases
      if(first.attr('data-highlight') === 'next'){
        var next = first.next()
        var timestamp =  next.attr('data-start') || next.attr('data-timestamp')
        url += '#'+timestamp
      } else if(first.attr('data-highlight') === 'transcript' || focus.attr('data-highlight') === 'transcript'){
        var timestamp =  first.attr('data-start') || first.attr('data-timestamp') || focus.attr('data-start') || focus.attr('data-timestamp')
        url += '#'+timestamp
      }
    }

    var pos = {
      position: 'absolute',
      left: (function(){
        var range = selection.getRangeAt(0)
        var rects = range.getClientRects()
        if(rects.length){
          return (rects[0].left + rects[0].right) /2
        }
      })()
    }

    if(!window.IS_TOUCH_SCREEN){ // For devices without native highlight UIs, display popup or below text, depending on where it fits
      pos.position = 'absolute'
      pos.top = function(){
        var range = selection.getRangeAt(0)
        var rects = range.getClientRects()
        var _top = (rects[0].top + rects[0].bottom) / 2
        var _bottom = (rects[rects.length-1].bottom + 60)
        var wBottom = $(window).height()

        if(_bottom < wBottom){ // Popup should be below content
          popup.addClass('socialPopup--inverse')
          return rects[rects.length-1].bottom + 60 + $(window).scrollTop()
        } else { // Popup should be above content
          popup.removeClass('socialPopup--inverse')
          return _top + $(window).scrollTop()
        }
      }
    }
    else { // For devices with native highlight UIs, always display below
      pos.top = function(){
        var range = selection.getRangeAt(0)
        var rects = range.getClientRects()
        if(rects.length){
          return rects[0].bottom + $(window).scrollTop()
        }
      }
    }

    popup.css(pos)
    .empty()
    .append('<span>Share this</span>' + socialLinks(url, document.title, text, text))

    if(text.length){
      popup.show()
    }

    popupOpen = true
    window.HIGHLIGHTED = text // so that socialLinks.js doesn't reuse the same one ever time – see callback
    if($('.socialPopup').length) return // otherwise this gets called twice in some versions of safari bc both events fire
    $('.body-wrap').append(popup)

  })

}

module.exports = socialHighlight
