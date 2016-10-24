var buildSuppInner = require( './buildSuppInner' );
var getUrlWithNoHash = require( './getUrlWithNoHash' );
var icon = require( './icon' );
var internalLink = require( './internalLink' );
var socialLinks = require( './socialLinks' );
var syncTimestamps = require( './syncTimestamps' );

var buildSupp = function( page, endpoint, queriedObject, callback, mainContentExists ){

  var aside = $( '<aside class="suppCont" />' );


  $.get( '/wp-json/v1/'+endpoint+'/'+queriedObject+'/supp', function(data){

    // ensure that repeated timestamps nest all content inside themselves
    var timestamps = [];
    var unmatched = [];
    var index = 0;

    $(data).each( function(){
      if( this.timestamp || this.timestamp === 0 && mainContentExists ){
        timestamps[this.timestamp] = timestamps[this.timestamp] || [];
        timestamps[this.timestamp].push( {
          type: this.type,
          data: this.data
        } );
      } else {
        unmatched.push( {
          type: this.type,
          data: this.data
        } );
      }
    } );

    for( var timestamp in timestamps ){
      var asideInner = $( '<ul class="suppCont-inner" data-timestamp="'+timestamp+'" />' );
      for( var i = 0, x = timestamps[timestamp].length; i<x; i++ ){
        var content = timestamps[timestamp][i];
        var suppContSingle = $( '<li tabindex="0" data-action="expand" data-supp="'+index+'" class="suppCont-single suppCont-single--'+content.type+'"/>' );
        var inner = '';
        var innerContent = buildSuppInner( content );
        var preview = innerContent.preview;
        var cont = innerContent.cont;

        suppContSingle.append( '<button class="suppCont-expand suppCont-expand--type" data-action="close-type">'+icon( content.type, 'suppExpand' )+'</button>' );
        if( content.class ) suppContSingle.addClass( 'suppCont-single--'+content.class );
        var inner =  '<div class="suppCont-singleInner">';
        inner +=        '<div class="suppCont-preview" aria-hidden>' + preview + '</div>';
        inner +=        '<div class="suppCont-content">'+cont;
        inner +=        '<div class="suppCont-share">Share this';
        inner +=          socialLinks( getUrlWithNoHash() + '#sc-'+index++, innerContent.preview, window.DESCRIPTION );
        inner +=        '</div>';
        inner +=      '</div></div">';
        suppContSingle.append( inner );
        suppContSingle.append( '<button data-action="close" class="suppCont-expand">'+ icon( 'expand', 'suppExpand' ) + '</button>' );
        asideInner.append( suppContSingle );
      }
      aside.append( asideInner )
    } // end for...in

    if( mainContentExists ) page.append( aside );

    if( unmatched.length ){
      var type = 'content'; // sanity check
      if( endpoint === 'timelines' ){
        type = 'timeline';
      } else if( endpoint === 'interviews' ){
        type = 'interview';
      }
      var unmatchedWrapper = $( '<section class="unmatched" />');
      var unmatchedList = $( '<ul class="unmatched-list" />' );
      for( var item in unmatched ){
        var content = unmatched[item];
        var unmatchedItem = $( '<li class="unmatched-item unmatched-item--'+content.type+'" />')
        var label = function( content ){
          switch( content.type ){
            case 'text':
              return content.data.content;
            case 'blockquote':
              return content.data.quote;
            default:
              return content.data.title;
          }
        }
        var innerContent = buildSuppInner( content ).cont;
        unmatchedItem.append( icon( content.type, 'type' ) + ' ' + label( content ) );
        unmatchedList.append( unmatchedItem );
        unmatchedItem.featherlight( {
          html: '<div class="suppCont-lightbox"><div class="suppCont-content">'+innerContent+'</div></div>',
          afterContent: function(){
            $( 'body' ).css( 'overflow', 'hidden' );
            $( '.featherlight-close-icon' ).html( icon( 'contract', 'suppContent-lightboxClose' )  );
          },
          afterClose: function(){
            //restore body scrolling
            $( 'body' ).css( 'overflow', '' );
          }
        } );
      }
      unmatchedWrapper.append( '<h3 class="unmatched-head">Additional content related to this ' + type + '</h3>' );
      unmatchedWrapper.append( unmatchedList );
      page.append( unmatchedWrapper );

    }

    if( endpoint === 'interviews' ){
      syncTimestamps( '.suppCont-inner', '.transcript-node', '.transcript' );
    } else if( endpoint === 'timelines' ){
      syncTimestamps( '.suppCont-inner', '.event', '.timeline' );
    }

    if( callback ) callback();

  } );
}

module.exports = buildSupp;
