var icon = require( './icon' );
var internalLink = require( './internalLink' );
var respImg = require( './respImg' );

var buildContentNode = function( data ){
  var content = $( '<article class="content content--'+data.type+'" data-id="'+data.id+'"/>' );
  var inner = $( '<div class="content-inner" />' );
  inner.append(  '<span class="content-type">'+icon(data.type, 'type')+' '+data.type+'</span>' );
  inner.append(  '<h3 class="content-head">'+data.title+'</h3>' );
  if( data.excerpt ){
    inner.append(  '<div class="content-excerpt">'+data.excerpt+'</div>' );
  }
  inner.append(  '<div class="content-link">View The '+data.type+' '+icon( 'right', 'link' )+'</div>' );
  content.append( inner );
  
  if(data.img_set){
    var img = '';
    img += '<img src="'+data.img_set.sizes.md+'" class="respImg-none" ';
    if( data.img_set.alt )     img += 'alt="'+data.img_set.alt+'" ';
    if( data.img_set.caption ) img += 'data-caption="'+data.img_set.caption+'" ';
    if( data.img_set.group )   img += 'data-group="'+data.img_set.group+'" ';
    img += ' />';

    content.append('<div class="content-imgWrapper">'+img+'</div>');
  }

  return internalLink( data, content[0].outerHTML );
};

module.exports = buildContentNode
