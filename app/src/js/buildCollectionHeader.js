var icon = require('./icon');
var respBg = require('./respBg');
var respImg = require('./respImg');
var socialLinks = require('./socialLinks');
var eqHeight = require('./eqHeight');

var buildCollectionHeader = function( page, data ){
  var header = $('<header class="contentHeader contentHeader--collection" />');
  var hero = $('<figure class="heroImg js-respBg" data-set="hero" data-id="'+data.image+'">');
  var inner = $('<div class="contentHeader-inner contentHeader-inner--hasBottom" />');
  var bottom = $('<div class="contentHeader-bottom" />');
  var imgWrapper = $('<div class="contentHeader-imgWrapper" />');
  header.append( hero );
  bottom.append( '<span class="contentHeader-type contentHeader-type--collection">'+icon( 'collection', 'type' )+'Collection</span>')
  bottom.append( '<h2 class="collection-head">'+data.name+'</h2>' );
  inner.append( bottom );
  imgWrapper.append( respImg.markup( data.image, 'feat_lg', 'respImg contentHeader-img', null, true ) );
  imgWrapper.append( '<div class="shareLinks">Share this collection'+socialLinks( data.link, data.title, data.description.replace(/(<([^>]+)>)/ig,""), data.description.replace(/(<([^>]+)>)/ig,"") )+'</div>')
  header.append( inner );
  header.append( imgWrapper );
  page.append( header );
  respBg( hero );
};

module.exports = buildCollectionHeader;
