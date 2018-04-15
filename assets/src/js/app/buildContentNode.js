const icon = require('./icon')
const internalLink = require('./internalLink')
const respImg = require('./respImg')
const shortid = require('shortid')

const buildContentNode = (item) => {
  const renderContent = ({
    type,
    id,
    title,
    excerpt,
    hits,
    hit_count,
    img_set,
    link
  }, className) => {
    const cutoff = 5
    const uid = shortid.generate()
    let imgHtml = '', hitHtml
    if(hits && hits.length > 0) {
      hitHtml = `
        <ul class="content-hits">
          ${hits.slice(0, cutoff).map((hit) => `<li
            class="content-data-sublink"
            data-sublink="${hit.timestamp}"
            tabindex="0"
            >${hit.text}</li>
          `).join('')}
        </ul>
        ${hits.length > cutoff ? `
          <ul class="content-hits hidden" id="${uid}">
            ${hits.slice(cutoff).map((hit) => `<li
              class="content-data-sublink"
              data-sublink="${hit.timestamp}"
              tabindex="0"
              >${hit.text}</li>
            `).join('')}
          </ul>
        ` : ''}
      `
    }

    if(img_set) {
      imgHtml = `
      <div class="content-imgWrapper">
        <img
          src="${img_set.sizes.md}"
          class="respImg-none"
          alt="${img_set.alt || ''}"
          ${img_set.caption ? ` data-caption="${img_set.caption}"` : null}
          ${img_set.group ? ` data-group="${img_set.group}"` : null}
        >
      </div>
      `
    }

    return `
      <article class="content content--${type} ${className || ''}" data-id="${id}">
        <div class="content-inner">
          <span class="content-type">${icon(type, 'type')} ${type}</span>
          <h3 class="content-head">
            ${title}
            ${!hit_count ? '' : `
              &nbsp<small>(${hit_count} total ${hit_count > 1 ? 'hits' : 'hit'})</small>
              ${hits.length > cutoff ? `
                <button class="content-cutoff" data-cutoff="#${uid}" data-alttext='View Less ${icon('up')}' data-nolink="true">Expand ${icon('down')}</button>
              ` : ''}
            `}
          </h3>
          <div class="content-excerpt">${hitHtml || excerpt}</div>
          <div class="content-link">View The ${type} ${icon('right', 'link')}</div>
        </div>
        ${imgHtml}
      </article>
    `
  }

  console.log(item)
  const content = `
    ${renderContent(item)}
    ${(item.children || []).map((child) => renderContent(child, 'content--child'))}
  `

  return internalLink({
    id: item.id,
    type: item.type,
    link: item.link
  }, content)
}

module.exports = buildContentNode
