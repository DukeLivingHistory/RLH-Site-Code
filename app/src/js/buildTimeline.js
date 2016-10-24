var icon = require('./icon');
var respImg = require('./respImg');
var socialLinks = require('./socialLinks');

var buildTimeline = function ( wrapper, events, intro, callback ){
  var intro = $( '<div class="content-intro">'+intro+'</div>' );
  var timeline = $( '<ul class="timeline"/>' );
  wrapper.append( intro );
  wrapper.append( timeline );

  $(events).each( function(i){
    var eventHtml = '';
    eventHtml += '<li id="'+i+'" class="event" data-start="'+this.event_date+'">';
    eventHtml += '<span class="event-dot"></span>';
    eventHtml   += '<date class="event-date">'+this.event_date+'</date>';
    eventHtml   += '<h3 class="event-head">'+this.title+'</h3>';
    if( this.image ){
      eventHtml += '<div class="event-imageWrapper">';
      eventHtml += respImg.markup( this.image, 'feat_lg', 'respImg', null, true );
      eventHtml += '</div>'
    }
    if( this.content.length ){
      eventHtml += '<div class="event-content">'+this.content+'</div>';
    }
    if( this.content_link ){
      eventHtml += '<a class="js-internalLink relatedItem relatedItem--'+this.content_link_type+'"';
      eventHtml += ' data-type="'+this.content_link_type+'s" ';
      eventHtml += ' data-id="'+this.content_link_id+'" ';
      eventHtml += 'href="'+this.content_link+'">'+icon( this.content_link_type, 'type' )+' '+this.content_link_text+'</a>';
    }
    eventHtml += '<div class="event-social">'+socialLinks( window.location.href.split('#')[0]+'#'+i, this.title, this.content )+'</div>';
    eventHtml += '</li>';
    var eventHtml = $( eventHtml );
    timeline.append( eventHtml );
    eventHtml.addClass('loaded');
  } );
  callback();
}

module.exports = buildTimeline;
