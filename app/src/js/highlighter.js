const getUrlWithNoHash = require('./getUrlWithNoHash')
const sharer = require('./sharer')

const highlighter = (target) => {
  const makePopup = (url, title, text, style, className) => {
    const shareLinks = sharer(url, title, text, {
      clipboardText: `${text}\n${url}`,
      copyText: 'Selected text plus link copied to clipboard!'
    })

    const markup = `
      <span class="socialPopup ${className}" style="${style}">
        ${shareLinks.render}
      </span>
    `

    const $popup = $('body').find('.socialPopup')

    if($popup.length > 0) {
      $popup.replaceWith(markup)
      shareLinks.attachHandlers()
    }
    else {
      $('body').append(markup)
      shareLinks.attachHandlers()
    }

    $('.socialPopup').on('click', (e) => {
      window.SOCIAL_POPUP_IS_OPEN = false
      setTimeout(() => {
        window.SOCIAL_POPUP_IS_OPEN = true
      }, 200)
    })

    setTimeout(() => {
      window.SOCIAL_POPUP_IS_OPEN = true
    }, 500)
  }

  const isInverse = (selection) => {
    const range = selection.getRangeAt(0)
    const rects = range.getClientRects()
    const offsetBottom = (rects[rects.length-1].bottom + 60) // bottom of last element
    const windowHeight = $(window).height()
    return offsetBottom < windowHeight
  }

  const getLeft = (selection) => {
    const range = selection.getRangeAt(0)
    const rects = range.getClientRects()
    if(rects.length) return (rects[0].left + rects[0].right) / 2
  }

  const getTop = (selection) => {
    if(window.IS_TOUCH_SCREEN) { // Always display small screens below to account for device UI
      const range = selection.getRangeAt(0)
      const rects = range.getClientRects()
      if(rects.length) return rects[0].bottom + $(window).scrollTop()
    }
    else {
      const range = selection.getRangeAt(0)
      const rects = range.getClientRects()
      const mid = (rects[0].top + rects[0].bottom) / 2
      const offsetBottom = (rects[rects.length-1].bottom + 60) // bottom of last element

      if(isInverse(selection)) { // Below content
        return offsetBottom + $(window).scrollTop()
      }
      else { // Above content
        return mid + $(window).scrollTop()
      }
    }
  }

  const handleChange = (e) => {
    let url = getUrlWithNoHash()
    const selection = document.getSelection()
    const text = selection.toString()

    if(text.length === 0) {
      $('.socialPopup').remove()
      return
    }

    const sanitized = text.replace('"', '&quot;')
    const { anchorNode, focusNode } = selection

    const $anchor = $(anchorNode.parentNode)
    const $focus = $(focusNode.parentNode)
    const $first = $anchor.index() < $focus.index() ? $anchor : $focus

    if(!$focus.parents(target).length) { // Is focused element in container?
      return
    }

    if(!$anchor.length || !$focus.length) return

    if(
      $anchor.data('highlight') ||
      $focus.data('highlight')
    ) {
      if($first.attr('data-highlight') === 'next') {
        const $next = $first.next()
        const timestamp = $next.attr('data-start') || $next.attr('data-timestamp')
        url = `${url}#${timestamp}`
      }
      else if(
        $first.attr('data-highlight') === 'transcript' ||
        $focus.attr('data-highlight') === 'transcript'
      ) {
        const timestamp = $first.attr('data-start') || $first.attr('data-timestamp') || $focus.attr('data-start') || $focus.attr('data-timestamp')
        url = `${url}#${timestamp}`
      }
    }


    const style = `
      position: absolute;
      left: ${getLeft(selection)|0}px;
      top: ${getTop(selection)|0}px;
      z-index: 1000000;
    `

    let className = ''
    if(isInverse(selection)) {
      className = 'socialPopup--inverse'
    }

    window.POPUP = setTimeout(() => {
      makePopup(url, document.title, text, style, className)
    }, 250)
  }

  $(document).on('selectionchange', handleChange)
  $(target).on('mouseup keypress touchend', handleChange)
}

module.exports = highlighter
