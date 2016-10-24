var internalLink = function( content, inner ){
  var item =  '<a class="js-internalLink"';
  item += ' data-type="'+content.type+'s" ';
  item += ' data-id="'+content.id+'" ';
  item += 'href="'+content.link+'">'+inner+'</a>';
  return item;
}

module.exports = internalLink;
