var cachebust = require('./cachebust');
var animatePage           = require('./animatePage');
var buildArchive          = require('./buildArchive');
var buildCollectionHeader = require('./buildCollectionHeader');
var buildCollectionFeed   = require('./buildCollectionFeed');
var buildTimeline         = require('./buildTimeline');
var buildTimelineHeader   = require('./buildTimelineHeader');
var buildInterviewsHeader = require('./buildInterviewsHeader');
var buildOtherInCollection = require('./buildOtherInCollection');
var buildTranscript       = require('./buildTranscript');
var buildSupp             = require('./buildSupp');
var eqHeight              = require('./eqHeight');
var socialHighlight       = require('./socialHighlight');
var stickyHeader          = require('./stickyHeader');
var syncAblePlayer        = require('./syncAblePlayer');
var respBg                = require('./respBg');
var respImg               = require('./respImg');
var Cookies               = require('js-cookie');

var buildPage = function( wrapper, endpoint, queriedObject, dir ){

  $( '[data-action="jumpToActive"], .socialPopup' ).remove(); // shouldn't have to do this but we do

  clearInterval( JUMPTOACTIVE ); // from syncAblePlayer – stop polling went creating new page

  var page = $( '<article class="page"/>' );

  $( 'body' ).attr( 'data-endpoint', endpoint );
  $( 'body' ).attr( 'data-id', queriedObject );

  if( queriedObject === 'archive' ){

    if( endpoint === 'search' ){

      var term = $('body').attr( 'data-search' );
      document.title = 'Search for '+term;

      $.get( '/wp-json/v1/'+endpoint+'/'+term+'?count='+COUNT+'&offset=0'+cachebust(true), function(data){
        buildArchive( page, data, endpoint );
        animatePage( wrapper, page, dir, function(){
          respImg.load( '.respImg' );
        } );
      } );

    } else {

      document.title = endpoint.charAt(0).toUpperCase() + endpoint.slice(1);

      if(endpoint === 'interviews' ){

        var order = Cookies.get('ARCHIVEORDER');
        var url = '/wp-json/v1/'+endpoint+'?order=' + order + '&count='+COUNT+cachebust(true);

        $.get(url, function(data){
          buildArchive( page, data, endpoint, ( endpoint === 'interviews' ) );
          animatePage( wrapper, page, dir, function(){
            respImg.load( '.respImg' );
          } );
        } );

      } else {

        $.get( '/wp-json/v1/'+endpoint+'?count='+COUNT+'&offset=0'+cachebust(true), function(data){
          buildArchive( page, data, endpoint, ( endpoint === 'interviews' ) );
          animatePage( wrapper, page, dir, function(){
            respImg.load( '.respImg' );
          } );
        } );
      }

    }
  } else {

    $.get( '/wp-json/v1/'+endpoint+'/'+queriedObject+cachebust(), function(data){

      document.title = data.name;
      DESCRIPTION = data.description;

      if( endpoint === 'timelines' ){


        buildTimelineHeader( page, data );
        buildTimeline( page, data.events, data.intro, function(){

          if( window.location.hash ){
            var hash = window.location.hash;
            setTimeout( function(){
              $( 'body, html' ).scrollTop( $( hash ).offset().top );
            }, TRANSITIONTIME );
          }

        } );

      } else if( endpoint === 'interviews' ){

        buildInterviewsHeader( page, data )
        buildTranscript( page, data.id, function(transcript){

          socialHighlight( '.transcript' );

          buildSupp( page, endpoint, queriedObject, function(supp){
            if( data.collections.length ){
              buildOtherInCollection( page, data.id, data.collections[0] );
            }

            syncAblePlayer(transcript, data.id, supp);
          }, transcript );
          stickyHeader( page, '.contentHeaderOuter', '.contentHeader-inner' );

        } );

      } else if( endpoint === 'collections' ){

        buildCollectionHeader( page, data );
        buildCollectionFeed( page, data );

      }

      animatePage( wrapper, page, dir, function(){
        if( endpoint === 'timelines' && $('.respImg').length < 1 ){
          buildSupp( page, endpoint, queriedObject, function(){
            if( data.collections.length ){
              buildOtherInCollection( page, data.id, data.collections[0] );
            }
          }, true );
          return;
        }
        respImg.load( '.respImg', function(){

          // run this as a callback so that height can be based on returned images
          if( endpoint === 'timelines' ){
            buildSupp( page, endpoint, queriedObject, function(){
              if( data.collections.length ){
                buildOtherInCollection( page, data.id, data.collections[0] );
              }
            }, true );
          }

        } );
        eqHeight( '.others-single' );
      } );

    } );
  }

}

module.exports = buildPage;
