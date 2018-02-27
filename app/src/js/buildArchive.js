const cachebust        = require('./cachebust')
const buildContentNode = require('./buildContentNode')
const cutoff           = require('./cutoff')
const icon             = require('./icon')
const respBg           = require('./respBg')
const sublink          = require('./sublink')
const Cookies          = require('js-cookie')
const qs               = require('query-string')

const buildArchive = function(
  page,
  {
    items,
    image,
    name,
    total_hits,
    results,
    error,
    message
  },
  endpoint,
  hasNav,
  mediaTypes
){
  const isCondensed = hasNav && (Cookies.get('ARCHIVEVIEW') === 'condense')
  const archiveOrder = Cookies.get('ARCHIVEORDER')

  let nav, load, subheading

  // Header
  const header = `
    <header class="contentHeader contentHeader--archive">
      <h2>${error || decodeURI(name)}</h2>
      ${!image ? '' : `<figure class="heroImage js-respBg" data-set="hero" data-id="${image}"/>`}
    </header>
  `

  // Feed
  const feed = `
    <ul class="content-feed ${isCondensed ? 'content-feed--contracted': ''}">
      ${
        items ?
          items.map((item) => buildContentNode(item)).join(' ') :
          (message || 'Sorry, no results were found.')
      }
    </ul>
  `

  // Nav
  if(hasNav) {
    let mediaSelect
    const sortOptions = [
      { value: 'abc_asc', label: 'A-Z' },
      { value: 'abc_desc', label: 'Z-A' },
      { value: 'date_desc', label: 'Date Interviewed' },
      { value: 'publish_desc', label: 'Date Published' },
      { value: 'date_asc', label: 'Date Interviewed (reverse)' },
      { value: 'publish_asc', label: 'Date Published (reverse)' }
    ]

    if(mediaTypes) {
      const mediaSelect = `
        <select name="media-select">
          ${mediaTypes.map((type) => (
            `<option value="${type.toLowerCase().replace(' ', '-')}">${type}</option>`
          )).join(' ')}
        </section>
      `
    }

    nav = `
      <div class="listView">
        <span class="listView-label">change view:</span>
        <input type="radio" name="list-view" value="explode" ${!isCondensed ? 'checked' : ''}>
        ${icon('explode', 'listView')}
        <input type="radio" name="list-view" value="condense" ${isCondensed ? 'checked' : ''}>
        ${icon('condense', 'listView')}
        <span class="listview-label">change view:</span>
        <select name="list-order">
          ${sortOptions.map(({value, label}) => (
            `<option value="${value}" ${archiveOrder === value ? 'selected' : ''}>${label}</option>`
          )).join(' ')}
        </select>
        ${!mediaSelect ? '' : `<span class="listView-label">media type:</span>${mediaSelect}`}
      </div>
    `
  }

  // Loader
  if(items && items.length >= COUNT){
    load = `<button data-offset="0" class="content-load">Load More</button>`
  }

  // Subheading
  if(total_hits && results) {
    subheading = `<p class="content-subheading">Showing ${total_hits} hits across ${results} files</p>`
  }

  // Construct Page
  const $append = $(`${header}${subheading || ''}${nav || ''}${feed}${load || ''}`)

  page.append($append)

  // Functionality
  const $feed = page.find('.content-feed')
  const $load = page.find('.content-load')
  const handleUpdate = function(loadedMore = false) {
    const $order = $append.find('select[name="list-order"]')
    const $media = $append.find('select[name="media-type"]')
    const offset = parseInt($load.attr('data-offset')) + COUNT
    const params = {
      order: $order.val(),
      offset: loadedMore ? offset : 0, // FIXME: Get correct value
      count: COUNT,
      include: $media ? $media.val() : null
    }

    const dest = (endpoint === 'search') ?
      endpoint+'/'+$('body').attr('data-search') :
      endpoint

    const url = `/wp-json/v1/${dest}?${qs.stringify(params)}${cachebust(true)}`

    console.log(url)

    $.get(url, ({items}) => {
      if(!items) {
        $load.hide()
        return
      }

      if(!loadedMore) $feed.empty()
      $feed.append(`
        ${items.map((item) => buildContentNode(item)).join(' ')}
      `)

      if(loadedMore) {
        if(items.length < COUNT){
          $load.hide()
        }
        else {
          $load.attr('data-offset', offset)
        }
      }
      else {
        $load.attr('data-offset', 0)
      }
    })
  }

  const changeView = function() {
    const value = $(this).val()
    const className = 'content-feed--contracted'
    const $feed = $('.content-feed')

    if(value === 'condense') {
      $feed.addClass(className)
    }
    else {
      $feed.removeClass(className)
    }
    Cookies.set('ARCHIVEVIEW', value)
  }

  const changeOrder = function() {
    const value = $(this).val()
    Cookies.set('ARCHIVEORDER', value)
  }

  // Event Listeners
  $append.on('click', 'input[name="list-view"]', changeView)
  $append.on('change', 'select[name="list-order"]', function(){
    handleUpdate()
    changeOrder.bind(this)()
  })
  $append.on('change', 'input[name="media-type"]', () =>{ handleUpdate() })
  page.on('click', '.content-load', () => { handleUpdate(true) })

  if(image){
    respBg($append.find('.respImg'))
  }

  // These are created in buildContentNode
  sublink($append.find('[data-sublink]'))
  cutoff($append.find('[data-cutoff]'))
}

module.exports = buildArchive
