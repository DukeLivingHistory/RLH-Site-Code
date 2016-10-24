var buildContentNode = require( './buildContentNode' );
var icon = require( './icon' );
var respImg = require( './respImg' );

var buildCollectionFeed = function( page, data ){
  var intro = $( '<div class="collection-intro" />' );
  var description = $( '<div class="collection-description">'+data.description+'</div>' );
  var search = $( '<div class="collection-introBottom"><label class="collection-searchLabel" for="filter">Search within this collection</label><span class="collection-search">'+icon( 'search', 'type' )+'<input name="filter" type="text" placeholder="Search" /></span></div>')
  var feed = $( '<ul class="collection" />' );
  var content = data.content;

  for( var i = 0, x = content.length; i<x; i++ ){
    feed.append( buildContentNode( content[i] ) );
  }

  intro.append( description );
  intro.append( search );
  page.append( intro );
  page.append( feed );

  search.find('input').on( 'keyup', function(e) {
    // after a delay in typing, search
    window.TIMEOUT = setTimeout( function(){
      $.get( '/wp-json/v1/collections/'+data.id+'?s='+$(this).val(), function( results ){
        var newContent = results.content;
        feed.empty();
        for( var i = 0, x = newContent.length; i<x; i++ ){
          feed.append( buildContentNode( newContent[i] ) );
          respImg.load( '.respImg' );
        }
      } );
    }.bind(this), 200 )
  } ).on( 'submit', function(e){
    e.preventDefault();
  } );


}

module.exports = buildCollectionFeed;
