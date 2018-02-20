var internalLink = function( content, inner ){
  const type = content.type + (content.type === 'rich-text' ? '' : 's') // TODO: Clean this up
  var item =  '<a class="js-internalLink"';
  item += ' data-type="'+type+'" ';
  item += ' data-id="'+content.id+'" ';
  item += 'href="'+content.link+'">'+inner+'</a>';
  return item;
}

module.exports = internalLink;
