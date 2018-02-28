const getUrlWithNoHash = require('./getUrlWithNoHash')

const highlighter = (target) => {
  const makePopup = (url, title, text, style, className) => {
    console.log('Popup made!')
    const markup = `
      <span class="socialPopup ${className}" style="${style}">
        ${sharer(url, title, text, {
          clipboardText: `${text}\n${url}`,
          copyText: 'Selected text plus link copied to clipboard!'
        })}
      </span>
    `

    const $popup = $('body').find('.socialPopup')

    if($popup.length > 0) {
      $popup.replaceWith(markup)
    }
    else {
      $('body').append(markup)
    }

    $('body').find('.socialPopup').on('mousedown touchend', (e) => {
      console.log('hey')
      e.preventDefault()
      e.stopPropagation()
    })
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

  $(target).on('selectionchange', function(e) {
    let url = getUrlWithNoHash()
    const selection = document.getSelection()
    const text = selection.toString()
    const sanitized = text.replace('"', '&quot;')
    const { anchorNode, focusNode } = selection

    if(!anchorNode || !! focusNode) return

    const $anchor = $(anchorNode.parentNode)
    const $focus = $(anchorNode.parentNode)
    const $first = $anchor.index() < $focus.index() ? $anchor : $focus

    if(
      $anchor.data('highlight') ||
      $focus.data('highlight')
    ) {
      if($first.data('highlight') === 'next') {
        const $next = $first.next()
        const timestamp = $next.data('start') || $next.attr('timestamp')
        url = `${url}#${timestamp}`
      }
      else if(
        $first.data('highlight') === 'transcript' ||
        $focus.data('highlight' === 'transcript')
      ) {
        const timestamp = $first.data('start') || $first.data('timestamp') || $focus.data('start') || $focus.data('timestamp')
        url = `${url}#${timestamp}`
      }
    }

    const style = `
      position: absolute;
      left: ${getLeft(selection)};
      top: ${getTop(selection)};
    `

    if(isInverse(selection)) {
      className = 'socialPopup--inverse'
    }

    makePopup(url, document.title, text, style, className)
  })
}

module.exports = highlighter
