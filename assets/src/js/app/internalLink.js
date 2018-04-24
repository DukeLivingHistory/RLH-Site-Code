const internalLink = function({
  type,
  id,
  link,
}, inner){
  type = type + (type === 'blog' ? '' : 's') // TODO: Clean this up
  if(window.SEARCHTERM) link = `${link}?search=${window.SEARCHTERM}`
  return `
    <a class="${type === 'blog' ? '' : 'js-internalLink'}"
      data-type=${type}
      data-id="${id}"
      href=${link}
    >${inner}</a>
  `
}

module.exports = internalLink;
