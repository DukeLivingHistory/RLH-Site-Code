const icon = require('./icon');
const respBg = require('./respBg');
const respImg = require('./respImg');
const sharer = require('./sharer')

const buildCollectionHeader = (
  page,
  {
    link,
    name,
    image
  }
) => {
  const shareLinks = sharer(link, name, name, {})

  const append = `
    <header class="contentHeader contentHeader--collection">
      <figure class="heroImg js-respBg" data-set="hero" data-id="${image}"/>
      <div class="contentHeader-inner contentHeader-inner--hasBottom">
        <div class="contentHeader-bottom">
          <span class="contentHeader-type contentHeader-type--collection">${icon('collection', 'type')}Collection</span>
          <h2 class="collection-head">${name}</h2>
        </div>
      </div>
      <div class="contentHeader-imgWrapper">
        ${respImg.markup({image}, 'feat_lg', 'respImg contentHeader-img', null, true)}
        <div class="shareLinks">
          Share this collection
          ${shareLinks.render}
        </div>
      </div>
    </header>
  `

  page.append(append)
  shareLinks.attachHandlers()
  respBg(page.find('.heroImg'))
}

module.exports = buildCollectionHeader
