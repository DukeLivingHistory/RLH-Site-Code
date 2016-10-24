var buildCollectionsList = require('./buildCollectionsList');
var buildConnected = require('./buildConnected');
var icon = require('./icon');
var respImg = require( './respImg' );
var socialLinks = require('./socialLinks');

var buildTimelineHeader = function ( wrapper, data ){
  var header = $( '<header class="contentHeader contentHeader--timeline"/>' );
  var inner = $( '<div class="contentHeader-inner" />' );
  var imgWrapper = $('<div class="contentHeader-imgWrapper" />');
  header.append(  '<span class="contentHeader-type">'+icon( 'timeline', 'type' )+'Timeline</span>' );
  inner.append( '<h2 class="contentHeader-head">'+data.name+'</h2>' );
  if( data.collections ){
    var collections = buildCollectionsList( data.collections );
    inner.append( collections );
  }
  if( data.related ){
    var related = buildConnected( data.related );
    inner.append( '<h3 class="contentHeader-relatedHead">Related to</h3>' );
    inner.append( related );
  }
  header.append( inner );
  if( data.image ){
    imgWrapper.append( respImg.markup( data.image, 'feat_lg', 'respImg contentHeader-img', null, true ) );
  }
  imgWrapper.append( '<div class="shareLinks">Share this collection'+socialLinks( data.link, data.title, data.intro.replace(/(<([^>]+)>)/ig,""), data.intro.replace(/(<([^>]+)>)/ig,"") )+'</div>')
  header.append( imgWrapper );
  wrapper.append( header );
}

module.exports = buildTimelineHeader;
