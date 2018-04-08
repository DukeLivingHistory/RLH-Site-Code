const internalLink = function({
  type,
  id,
  link
}, inner){
  type = type + (type === 'post' || type === 'interactive' ? '' : 's') // TODO: Clean this up
  return `
    <a class="${type === 'post' ? '' : 'js-internalLink'}"
      data-type=${type}
      data-id="${id}"
      href=${link}
    >${inner}</a>
  `
}

module.exports = internalLink;
