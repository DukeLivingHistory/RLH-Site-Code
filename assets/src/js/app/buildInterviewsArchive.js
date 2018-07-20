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
  }) => `
  ${img_set ? `<img src="${img_set.sizes.md}"/>` : ''}
  <li class="content-gridNode ${excerpt ? 'hasExcerpt' : ''}">
    <div class="content-gridNode-inner">
      ${collection ? `<div class="content-gridNode-collection">${collection}</div>` : ''}
      <div class="content-gridNode-title">${title}</div>
      ${subtitle ? `<div class="content-gridNode-subtitle">${subtitle}</div>` : ''}
      ${excerpt ? `<div class="content-gridNode-excerpt">
        ${limit(excerpt)}
      </div>` : ''}
    </div>
  </li>
  `

  const feed = `
  <ul class="content-grid">
    ${items.map(item => internalLink(item, node(item))).join('')}
  </ul>
  `

  const $append = $(`${header}${feed}`)
  page.append($append)
}

module.exports = buildInterviewsArchive
