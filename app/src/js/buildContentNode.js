var icon = require('./icon');
var internalLink = require('./internalLink');
var respImg = require('./respImg');

var buildContentNode = function({
  type,
  id,
  title,
  excerpt,
  img_set,
}){
  var content = $('<article class="content content--'+type+'" data-id="'+id+'"/>');
  var inner = $('<div class="content-inner" />');
  inner.append( '<span class="content-type">'+icon(type, 'type')+' '+type.replace('-', ' ')+'</span>');
  inner.append( '<h3 class="content-head">'+title+'</h3>');
  if(excerpt){
    inner.append( '<div class="content-excerpt">'+excerpt+'</div>');
  }
  inner.append( '<div class="content-link">View The '+type+' '+icon('right', 'link')+'</div>');
  content.append(inner);

  if(img_set){
    var img = '';
    img += '<img src="'+img_set.sizes.md+'" class="respImg-none" ';
    if(img_set.alt)     img += 'alt="'+img_set.alt+'" ';
    if(img_set.caption) img += 'data-caption="'+img_set.caption+'" ';
    if(img_set.group)   img += 'data-group="'+img_set.group+'" ';
    img += ' />';

    content.append('<div class="content-imgWrapper">'+img+'</div>');
  }

  return internalLink(data, content[0].outerHTML);
};

module.exports = buildContentNode
