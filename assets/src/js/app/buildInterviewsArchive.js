const internalLink = require('./internalLink')

const buildInterviewsArchive = (
  page,
  {
    items,
    image,
    name,
    error,
  }
) => {
  const header = `
    <header class="contentHeader contentHeader--archive">
      <h2>${error || decodeURI(name)}</h2>
      ${!image ? '' : `<figure class="heroImage js-respBg" data-set="hero" data-id="${image}"/>`}
    </header>
`

  const limit = s => {
    if(s.length < 120) {
      return s
    }
    return s.substring(0, 120) + '...'
  }

  const node = ({
    id,
    title,
    subtitle,
    excerpt,
    img_set,
    link,
    collection,
    card_alignment,
  }) => `
  ${img_set ? `<img src="${img_set.sizes.md}"/>` : ''}
  <li class="content-gridNode ${excerpt ? 'hasExcerpt' : ''} ${card_alignment || 'right'}">
    <div class="content-gridNode-inner">
      <div class="content-gridNode-title">${title}</div>
      ${subtitle ? `<div class="content-gridNode-subtitle">${subtitle}</div>` : ''}
      ${excerpt ? `<div class="content-gridNode-excerpt">
        ${excerpt}
      </div>` : ''}
    </div>
  </li>
  `

  const collections = items.reduce((groups, item) => {
    if(item.collection) {
      const existing = groups[item.collection] || []
      groups[item.collection] = [...existing, item]
    } else {
      groups.ungrouped = [...groups.ungrouped, item]
    }
    return groups
  }, { ungrouped: [] } )

  const sort = ([a], [b]) => {
    if(a === 'ungrouped') {
      return 1
    }
    if(b === 'ungrouped') {
      return -1
    }
    return a.localeCompare(b)
  }

  const feed = Object.entries(collections).sort(sort).map(([name, items]) => `
    <div class="content-gridCollection">
      ${name !== 'ungrouped' ? `<h2>${name}</h2>` : ''}
      <ul class="content-grid">
        ${items.map(item => internalLink(item, node(item))).join('')}
      </ul>
    </div>
  `).join('')

  const $append = $(`${header}${feed}`)
  page.append($append)
}

module.exports = buildInterviewsArchive
