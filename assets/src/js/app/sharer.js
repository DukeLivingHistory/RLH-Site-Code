const Clipboard = require('clipboard')
const fb = require('facebook-share-link')
const shortid = require('shortid')
const icon = require('./icon')

const sharer = (
  url = window.location.href,
  title,
  quote,
  {
    clipboardText,
    copyText
  } = {}
) => {
  const isFbProvided = !!window.FB_APP_ID
  const id = shortid.generate()

  const options = {
    url,
    title,
    quote,
    clipboardText: clipboardText || url,
    window: `
      height: 450,
      width: 550,
      top=${$(window).height / 2 - 275},
      left=${$(window).width() / 2 - 225},
      toolbar=0,
      location=0,
      menubar=0
      directories=0,
      scrollbars=0
    `
  }

  const render = `
    <ul class="social social--inline" data-share-id=${id}>
      ${isFbProvided ? `<li data-soc="fb" tabindex="0"><span>Share on Facebook</span>${icon('facebook', 'social')}</li>` : ''}
      <li data-soc="tw" tabindex="0"><span>Share on Twitter</span>${icon('twitter', 'social')}</li>
      <li data-clipboard-text="${options.clipboardText}" data-soc="link" tabindex="0"><span>Share on URL</span>${icon('link', 'social')}</li>
    </ul>
  `

  const attachClipboardHandlers = (clipboard) => {
    clipboard.on('success', () => {
      $('body').append(`
        <div class="socialCopy socialCopy--success" style="position: fixed; right: 1em; bottom: 1em;">
          ${copyText || 'Link copied to clipboard!'}
        </div>
      `)
      setTimeout(() => {
        $('.socialCopy').remove()
      }, 2000)
    })
  }

  const attachHandlers = (specificId, specificOptions) => {
    const _id = specificId || id
    const _options = specificOptions || options

    const clipboard = new Clipboard(`[data-share-id="${_id}"] [data-soc="link"]`)

    attachClipboardHandlers(clipboard)

    $('body').on('click', `[data-share-id="${_id}"] li`, function(e) {
      const type = $(this).data('soc')
      let link = ''
      switch(type) {
        case "fb":
          if(!isFbProvided) break
          const share = fb(window.FB_APP_ID)
          link = share({
            href: _options.url,
            display: 'popup',
            quote: _options.quote
          })
          window.open(link, 'fbShareWindow', _options.window)
          return
        case "tw":
          const url = encodeURIComponent(_options.url)
          const quote = encodeURIComponent(_options.quote)
          link = `http://twitter.com/intent/tweet?url=${url}&text=${quote}`
          window.open(link, 'twShareWinow', _options.window)
          return
        case "link":
          e.preventDefault()
          break
      }
    })
  }

  return {
    render,
    attachHandlers: (id, options) => { attachHandlers(id, options) },
    id,
    options
  }
}

module.exports = sharer
