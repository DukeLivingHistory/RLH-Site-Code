const buildCollectionsList = require('./buildCollectionsList')
const buildConnected = require('./buildConnected')
const icon = require('./icon')
const respImg = require('./respImg')
const sharer = require('./sharer')

const buildTimelineHeader = (
  page,
  {
    link,
    name,
    image,
    introduction,
    related,
    collections
  },
  type = null
) => {
  const shareLinks = sharer(link, name, name, {})

  console.log(buildConnected(related))

  const append = `
    <header class="contentHeader contentHeader--timeline">
      ${type !== false ? `
        <span class="contentHeader-type">
          ${icon(type, 'type')}
          ${type || 'Timeline'}
        </span>
      ` : ''}
      <div class="contentHeader-inner">
        <h2 class="contentHeader-head">${name}<?h2>
        ${collections ? buildCollectionsList(collections) : ''}
        ${introduction ? `<div class="contentHeader-introduction">${introduction}</div>` : ''}
        ${related ? `
          <h3 class="contentHeader-relatedHead">Related to</h3>
          ${buildConnected(related)}
        ` : ''}
      </div>
      <div class="contentHeader-imgWrapper">
        ${image ? respImg.markup(image, 'feat_lg', 'respImg contentHeader-img', null, true) : ''}
        <div class="shareLinks">
          Share this collection
          ${shareLinks.render}
        </div>
      </div>
    </header>
  `

  page.append(append)
  shareLinks.attachHandlers()
}

module.exports = buildTimelineHeader
