const icon = require('./icon');
const internalLink = require('./internalLink');
const respImg = require('./respImg');

const buildContentNode = function({
  type,
  id,
  title,
  excerpt,
  hits,
  img_set,
  link
}){
  let imgHtml = '', hitCount
  if(hits && hits.length > 0) {
    hitCount = hits.length
    hits = `
      <ul class="content-hits">
        ${hits.map((hit) => `<li
          class="content-data-sublink"
          data-sublink=${hit.timestamp}
          >${hit.text}</li>
        `).join('')}
      </ul>
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

  const content = `
    <article class="content content--${type}" data-id="${id}">
      <div class="content-inner">
        <span class="content-type">${icon(type, 'type')} ${type}</span>
        <h3 class="content-head">
          ${title}
          ${!hits ? '' : `&nbsp;<small>(${hitCount} total hits)</small>`}
        </h3>
        <div class="content-excerpt">${hits || excerpt}</div>
        <div class="content-link">View The ${type} ${icon('right', 'link')}</div>
      </div>
      ${imgHtml}
    </article>
  `
  return internalLink({
    id,
    type,
    link
  }, content);
};

module.exports = buildContentNode
