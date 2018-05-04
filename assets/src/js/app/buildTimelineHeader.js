const buildCollectionsList = require('./buildCollectionsList')
const buildConnected = require('./buildConnected')
const icon = require('./icon')
const respImg = require('./respImg')
const sharer = require('./sharer')

const buildTimelineHeader = (
  page,
  {
    id,
    link,
    name,
    image,
    introduction,
    description, // Backwards compatibility for interactives
    related,
    collections
  },
  type = null
) => {
  introduction = introduction || description
  const shareLinks = sharer(link, name, name, {})

  const append = `
    <header class="contentHeader contentHeader--timeline">
      ${type !== false ? `
        <span class="contentHeader-type">
          ${icon(type, 'type')}
          ${type || 'Timeline'}
        </span>
      ` : ''}
      <div class="contentHeader-inner">
        <h2 class="contentHeader-head">${name}</h2>
        ${collections ? buildCollectionsList(collections) : ''}
        ${introduction ? `<div class="contentHeader-introduction">${introduction}</div>` : ''}
        ${related ? `
          <h3 class="contentHeader-relatedHead">Related to</h3>
          ${buildConnected(related)}
        ` : ''}
        ${type === 'interactive' ? (
          `<span class="contentHeader-selectWrapper" id="selectWrap-${id}" style="display: none;">
            <select class="contentHeader-select" id="select-${id}">
              <option value="null">Contents</option>
            </select>
          </span>`
        ) : null}
      </div>
      <div class="contentHeader-imgWrapper">
        ${image ? respImg.markup(image, 'feat_lg', 'respImg contentHeader-img', null, true) : ''}
        <div class="shareLinks">
          Share
          ${shareLinks.render}
        </div>
      </div>
    </header>
  `

  page.append(append)
  shareLinks.attachHandlers()
}

module.exports = buildTimelineHeader
