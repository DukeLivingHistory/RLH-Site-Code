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
  ${img_set ? `<img src="${img_set.sizes.md}"/>` : ''}
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

  const collections = items.reduce((groups, item) => {
    if(item.collection) {
      const existing = groups[item.collection] || []
      groups[item.collection] = [...existing, item]
    } else {
      groups.ungrouped = [...groups.ungrouped, item]
    }
    return groups
  }, { ungrouped: [] } )

  // const sort = ([a], [b]) => {
  //   if(a === 'ungrouped') {
  //     return 1
  //   }
  //   if(b === 'ungrouped') {
  //     return -1
  //   }
  //   return a.localeCompare(b)
  // }

  // const feed = Object.entries(collections).sort(sort).map(([name, items]) => `
  //   <div class="content-gridCollection">
  //     ${name !== 'ungrouped' ? `<h2>${name}</h2>` : ''}
  //     <ul class="content-grid">
  //       ${items.map(item => internalLink(item, node(item))).join('')}
  //     </ul>
  //   </div>
  // `).join('')

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
      { value: 'publish', label: 'Date Published (reverse)' }
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
      makeFeed(val)
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

  makeFeed('abc')
}

module.exports = buildInterviewsArchive
