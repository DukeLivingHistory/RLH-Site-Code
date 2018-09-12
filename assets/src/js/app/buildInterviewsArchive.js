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
    interview_date,
  }) => `
  ${img_set ? `<div class="content-gridImg"><img src="${img_set.sizes.md}"/></div>` : ''}
  <li class="content-gridNode">
    <div class="content-gridNode-inner">
      <div class="content-gridNode-title">${title}</div>
      ${subtitle ? `<div class="content-gridNode-subtitle">${subtitle}</div>` : ''}
      ${interview_date ? `<div class="content-gridNode-date">
        ${new Date(interview_date * 1000).toLocaleDateString('en-US', {
          year: 'numeric', month: 'long', day: 'numeric'
        })
      }</div>` : ''}
    </div>
  </li>
  `

  const sorts = {
    abc: (a, b) => a.abc_term ? a.abc_term.localeCompare(b.abc_term) : -1,
    abc_desc: (a, b) => a.abc_term ? -1 * a.abc_term.localeCompare(b.abc_term) : -1,
    date: (a, b) => a.interview_date - b.interview_date,
    date_desc: (a, b) => -1 * (a.interview_date - b.interview_date),
    publish: (a, b) => a.publish_date - b.publish_date,
    publish_desc: (a, b) => -1 * (a.publish_date - b.publish_date),
  }

  const nav = (selected) => {
    const sortOptions = [
      { value: 'abc', label: 'A-Z' },
      { value: 'abc_desc', label: 'Z-A' },
      { value: 'date_desc', label: 'Date Interviewed' },
      { value: 'date', label: 'Date Interviewed (reverse)' },
      { value: 'publish_desc', label: 'Date Published' },
      { value: 'publish', label: 'Date Published (reverse)' },
      { value: 'collection', label: 'Collection Name' },
      { value: 'collection_reverse', label: 'Collection Name (reverse)' },
    ]


    const $nav = $(`
      <div class="listView">
        <span class="listview-label">change view:</span>
        <select name="list-order">
          ${sortOptions.map(({value, label}) => (
            `<option value="${value}" ${selected === value ? 'selected' : ''}>${label}</option>`
          )).join(' ')}
        </select>
      </div>
    `)

    $nav.on('change', `select`, function(e) {
      e.preventDefault()
      const val = $(this).val()
      if(val === 'collection' || val === 'collection_reverse') {
        makeCollectionFeed(val)
      } else {
        makeFeed(val)
      }
    })

    return $nav
  }

  const makeFeed = (sortType) => {
    const feed = `<ul class="content-grid">
      ${items
        .sort(sorts[sortType])
        .map(item => internalLink(item, node(item))).join('')}
    </ul>`
    page.empty()
    page.append(header)
    page.append(nav(sortType))
    page.append(feed)
  }

  const makeCollectionFeed = (val) => {
    const collections = items.reduce((groups, item) => {
      console.log(item)
      if(item.collections) {
        item.collections.forEach(collection => {
          const existing = groups[collection] || []
          groups[collection] = [...existing, item]
        })
      } else {
        groups.ungrouped = [...groups.ungrouped, item]
      }
      return groups
    }, { ungrouped: [] } )

    const sort = ([a], [b]) => {
      const reverse = (val === 'collection_reverse') ? -1 : 1
      if(a === 'ungrouped') return 1
      if(b === 'ungrouped') return -1
      return a.localeCompare(b) * reverse
    }

    const feed = Object.entries(collections).sort(sort).map(([name, items]) => `
      <div class="content-gridCollection">
        ${name !== 'ungrouped' ? `<h2>${name}</h2>` : `<h2>Standalone Interviews</h2>`}
        <ul class="content-grid">
          ${items.map(item => internalLink(item, node(item))).join('')}
        </ul>
      </div>
    `).join('')
    page.empty()
    page.append(header)
    page.append(nav(val))
    page.append(feed)
  }

  makeFeed('abc')
}

module.exports = buildInterviewsArchive
