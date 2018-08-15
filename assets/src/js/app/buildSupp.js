const cachebust = require('./cachebust')
const buildSuppInner = require('./buildSuppInner')
const getUrlWithNoHash = require('./getUrlWithNoHash')
const icon = require('./icon')
const internalLink = require('./internalLink')
const sharer = require('./sharer')
const syncTimestamps = require('./syncTimestamps')

const buildSupp = (
  page,
  endpoint,
  queriedObject,
  callback,
  mainContentExists
) => {
  $.get(`/wp-json/v1/${endpoint}/${queriedObject}/supp${cachebust()}`, (results) => {
    const timestamps = {}
    const unmatched = []

    results.forEach(({ type, data, open, timestamp}) => {
      timestamp = timestamp.toString()
      if(timestamp || (timestamp === 0 && mainContentExists)) {
        timestamps[timestamp] = timestamps[timestamp] || []
        timestamps[timestamp].push({ type, data, open })
      }
      else {
        unmatched.push({ type, data })
      }
    })

    let index = 0
    const shares = []
    let inner = ''

    for(const timestamp in timestamps) {
      inner = inner + `
        <ul class="suppCont-inner" data-timestamp="${timestamp}">
          ${timestamps[timestamp].map((node) => {
            const { preview, cont } = buildSuppInner(node)
            const url = `${getUrlWithNoHash()}#sc-${index}`

            const shareLinks = sharer(
              url,
              preview,
              preview,
              { clipboardText: `${preview}\n${url}` }
            )


            shares.push({
              id: shareLinks.id,
              options: shareLinks.options
            })

            return `
              <li tabindex="0"
                ${node.open ? `data-opendefault="true"` :''}
                data-action="expand"
                data-supp="${index}"
                class="suppCont-single suppCont-single--${node.type} ${node.class ? `suppCont-single--${node.class}` : ''}"
              >
                <button class="suppCont-expand suppCont-expand--type" data-action="close-type">
                  ${icon(node.type, 'suppExpand')}
                </button>
                <div class="suppCont-singleInner">
                  <div data-suppcont="${preview}" class="suppCont-preview" aria-hidden>${preview}</div>
                  <div class="suppCont-content">${cont}
                    <div class="suppCont-share">
                      Share this
                      ${shareLinks.render}
                    </div>
                  </div>
                </div>
                <button data-action="close" class="suppCont-expand">
                  ${icon('expand', 'suppExpand')}
                </button>
              </li>
            `
          }).join(' ')}
        </ul>
      `
      index = index + 1
    }

    const aside = `<aside class="suppCont">${inner}</aside>`
    if(mainContentExists) page.append(aside)

    shares.forEach(({id, options}) => { sharer().attachHandlers(id, options) })

    if(unmatched.length) {
      let type = 'content'
      if(endpoint === 'timelines') {
        type = 'timeline'
      }
      else if(endpoint === 'interviews') {
        type = 'interview'
      }

      const unmatchedWrapper = `
        <section class="unmatched">
          <h3 class="unmatched-head">Additional content related to this ${type}</h3>
          <ul class="unmatched-list">
            ${unmatched.map((item) => {
              const content = buildSuppInner(item).cont
              let label = item.data.title
              switch(item.type) {
                case 'text':
                  label = item.data.content
                case 'blockquote':
                  label = item.data.quote
              }

              return `
                <li data-content='${content}' class="unmatched-item unmatched-item--${item.type}">
                  ${icon(item.type, 'type')} ${label}
                </li>
              `
            }).join(' ')}
          </ul>
        </section>
      `

      page.append(unmatchedWrapper)
    }

    if(endpoint === 'interviews' || endpoint === 'interactives'){
      syncTimestamps('.suppCont-inner', '.transcript-node', '.transcript')
    }
    else if(endpoint === 'timelines'){
      syncTimestamps('.suppCont-inner', '.event', '.timeline')
    }

    $('[data-content]').each(function(){
      const content = $(this).data('content')
      $(this).featherlight({
        html: `
          <div class="suppCont-lightbox">
            <div class="suppCont-content">${content}</div>
          </div>
        `,
        afterContent: () => {
          $('body').css('overflow', 'hidden')
          $('.featherlight-close-icon').html(icon('contract', 'suppContent-lightboxClose'))
        },
        afterClose: () => {
          $('body').css('overflow', '')
        }
      })
    })

    if(callback) callback({
      timestamps,
      unmatched
    })
  })
}

module.exports = buildSupp
