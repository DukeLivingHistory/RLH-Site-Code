var icon = require( './icon' );
var internalLink = require( './internalLink' );
var respImg = require( './respImg' );

var buildSuppInner = function( content ){

  var preview = '';
  var cont = '';

  switch( content.type ){
    case 'blockquote':
      preview = content.data.quote;
      cont =  '<blockquote class="suppCont-quote">'+content.data.quote+'&rdquo;';
      cont +=  '<footer class="suppCont-attribution">&mdash; '+content.data.attribution+'</footer>';
      cont += '</blockquote>';
      break;
    case 'externallink':
      preview = content.data.title;
      cont += '<span class="suppCont-contentTitle">'+content.data.title+'</span>';
      if( content.data.description ) cont += '<p>'+content.data.description+'</p>';
      cont += '<a target="_blank" href="' + content.data.link_url + '">';
      cont +=  ( content.data.link_text || 'Visit Link' ) + icon( 'right', 'link' );
      cont += '</a>';
      content.type = 'link';
      break;
    case 'file':
      preview = content.data.title;
      cont += '<span class="suppCont-contentTitle">'+content.data.title+'</span>';
      if( content.data.description ) cont += '<p>'+content.data.description+'</p>';
      cont += '<a target="_blank" href="' + content.data.file + '">';
      cont +=  'Download ' + icon( 'right', 'link' );
      cont += '</a>';
      break;
    case 'gallery':
      preview = content.data.title;
      cont += '<span class="suppCont-contentTitle">'+content.data.title+'</span>';
      if( content.data.description ) cont += '<p>'+content.data.description+'</p>';
      cont += '<div class="suppCont-gallery">';
      for( var i = 0, x = content.data.imgs.length; i < x; i++ ){
        var img = content.data.imgs[i];
        cont += '<span class="suppCont-galleryImage">';
        cont += respImg.markup( img.img_id, 'feat_xs', 'respImg-defer', {
          alt: img.alt,
          caption: img.caption,
          group: content.data.title
        } );
        cont += '</span>';
      }
      cont += '</div>'
      break;
    case 'image':
      preview = content.data.title;
      cont += '<span class="suppCont-contentTitle">'+content.data.title+'</span>';
      cont += respImg.markup( content.data.img_id, 'feat_sm', 'respImg-defer', {
        alt: content.data.alt,
        caption: content.data.caption
      } );
      if( content.data.caption ) cont += '<p>'+content.data.caption+'</p>';
      break;
    case 'internallink':
      preview = content.data.title;
      content.type = content.data.type;
      content.class = content.data.type;
      cont += '<span class="suppCont-contentTitle">'+content.data.title+'</span>';
      cont += respImg.markup( content.data.feat_img, 'feat', 'respImg-defer gallery-single' );
      cont += '<p>'+(content.data.link_description || content.data.description)+'</p>';
      cont += internalLink( content.data, 'View ' + content.type + icon( 'right', 'link' ) );
      break;
    case 'map_location':
      var zoom = content.data.zoom || 17;
      var map_url = 'https://maps.googleapis.com/maps/api/staticmap?center='+content.data.coords.lat+','+content.data.coords.lng+'&size=600x300&zoom='+zoom+'&markers=color:red%7C'+content.data.coords.lat+','+content.data.coords.lng+'&key='+MAPS_APP_ID; // TODO: make API key a site option
      preview = content.data.title;
      cont += '<span class="suppCont-contentTitle">'+content.data.title+'</span>';
      cont += '<img src="' + map_url + '" alt="Map of '+content.data.title+'" />';
      break;
    case 'text':
      var tempHtml = document.createElement('div');
      tempHtml.innerHTML = content.data.content;
      preview = tempHtml.textContent || tempHtml.innerText;
      cont = content.data.content;
      break;
  }

  return {
    preview: preview,
    cont: cont
  }
}

module.exports = buildSuppInner;
