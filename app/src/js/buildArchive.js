var cachebust = require('./cachebust');
var buildContentNode = require( './buildContentNode' );
var icon             = require( './icon' );
var respBg           = require( './respBg' );
var Cookies          = require('js-cookie');

var buildArchive = function( page, data, endpoint, canBeCondensed ){
  var header = $( '<header class="contentHeader contentHeader--archive">' );
  if( data.image ) {
    var hero = $('<figure class="heroImg js-respBg" data-set="hero" data-id="'+data.image+'">');
  }
  var feed = $( '<ul class="content-feed"/>' );
  var load = $( '<button class="content-load">Load More</button>' );
  var isAbc = false;

  header.append( '<h2>'+decodeURI( data.name )+'</h2>' );
  if( data.image ){
    header.append( hero );
    respBg( hero );
  }


  if( data.items ){
    for( var i = 0, x = data.items.length; i < x; i++ ){
      feed.append( buildContentNode( data.items[i] ) );
    }
  } else {
    feed.append( 'Sorry, no results were found.' );
  }

  page.append( header );
  if( canBeCondensed ){
    var btnCondense = $( '<input type="radio" name="list-view" value="condense">' );
    var btnExplode   = $( '<input type="radio" name="list-view" value="explode">' );

    if( Cookies.get('ARCHIVEVIEW') === 'explode' ){
      btnExplode.attr( 'checked', 'checked' );
    } else {
      isAbc = true;
      btnCondense.attr( 'checked', 'checked' );
      feed.addClass( 'content-feed--contracted' );
    }

    var listView = $( '<div class="listView"/>' );
    listView
      .append( '<span class="listView-label">change view:</span>' )
      .append( btnExplode )
      .append( icon( 'explode', 'listView' ) )
      .append( btnCondense )
      .append( icon( 'condense', 'listView' ) );
    page.append( listView );
  }
  page.append( feed );
  if( data.items && data.items.length >= COUNT ){
    page.append( load );
  }

  load.data( 'offset', 0 );
  load.click( function(){
    load.data( 'offset', load.data( 'offset' ) + 1 );
    var dest = endpoint === 'search' ? endpoint+'/'+$('body').attr('data-search') : endpoint;
    var params = '';
    if(isAbc) params = params = '&order=abc';

    var url  = '/wp-json/v1/'+dest+'?count='+COUNT+'&offset=' + ( load.data( 'offset' ) * COUNT )+cachebust(true) + params;
    console.log(url);
    $.get(url, function(data){
      for( var i = 0, x = data.items.length; i < x; i++ ){
        feed.append( buildContentNode( data.items[i] ) );
      }
      if( data.items.length < COUNT ){
        load.hide();
      }
    } );
  } );

  if( listView ){
    listView.click( function(){
      load.data('offset', 0);
      var selected = $( 'input[name="list-view"]:checked' ).val();
      Cookies.set( 'ARCHIVEVIEW', selected );
      if( selected === 'condense' ){
        isAbc = true;
        var dest = endpoint === 'search' ? endpoint+'/'+$('body').attr('data-search')  : endpoint;
        $.get( '/wp-json/v1/'+dest+'?order=abc&offset=0&count='+COUNT+cachebust(true), function(data){
          feed.empty();
          feed.addClass( 'content-feed--contracted' );
          for( var i = 0, x = data.items.length; i < x; i++ ){
            feed.append( buildContentNode( data.items[i] ) );
          }
          if( data.items.length < COUNT ){
            load.hide();
          } else {
            load.show();
          }
        } );

      } else {
        isAbc = false;
        var dest = endpoint === 'search' ? endpoint+'/'+$('body').attr('data-search')  : endpoint;
        // previous count
        $.get( '/wp-json/v1/'+dest+'?offset=0&count='+COUNT+cachebust(true), function(data){
          feed.empty();
          feed.removeClass( 'content-feed--contracted' );
          for( var i = 0, x = data.items.length; i < x; i++ ){
            feed.append( buildContentNode( data.items[i] ) );
          }
          if( data.items.length < _count ){
            load.hide();
          } else {
            load.show();
          }
        } );

      }
    } );
  }

  $( 'body' ).on( 'click', '.content', function(e){
    var link             = $(this).find('.js-internalLink')
    var target           = link.attr( 'href' );
    var _endpoint        = link.attr( 'data-type' );
    var _queriedObject   = link.attr( 'data-id' );
  } );
}

module.exports = buildArchive;
