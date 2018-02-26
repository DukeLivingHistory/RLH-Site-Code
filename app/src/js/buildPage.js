const cachebust = require('./cachebust')
const animatePage            = require('./animatePage')
const buildArchive           = require('./buildArchive')
const buildCollectionHeader  = require('./buildCollectionHeader')
const buildCollectionFeed    = require('./buildCollectionFeed')
const buildTimeline          = require('./buildTimeline')
const buildTimelineHeader    = require('./buildTimelineHeader')
const buildInterviewsHeader  = require('./buildInterviewsHeader')
const buildOtherInCollection = require('./buildOtherInCollection')
const buildTranscript        = require('./buildTranscript')
const buildSupp              = require('./buildSupp')
const eqHeight               = require('./eqHeight')
const socialHighlight        = require('./socialHighlight')
const stickyHeader           = require('./stickyHeader')
const syncAblePlayer         = require('./syncAblePlayer')
const respBg                 = require('./respBg')
const respImg                = require('./respImg')
const Cookies                = require('js-cookie')

const buildPage = function(wrapper, endpoint, queriedObject, dir){
  $('[data-action="jumpToActive"], .socialPopup').remove()
  clearInterval(JUMPTOACTIVE) // from syncAblePlayer – stop polling went creating new page
  const page = $('<article class="page"/>')
  $('body').attr('data-endpoint', endpoint)
  $('body').attr('data-id', queriedObject)
  if(queriedObject === 'archive'){
    if(endpoint === 'search'){
      const term = $('body').attr('data-search').replace('+', ' ')
      window.SEARCHTERM = term
      document.title = 'Search for '+term
      $.get('/wp-json/v1/'+endpoint+'/'+term+'?count='+COUNT+'&offset=0'+cachebust(true), function(data){
        buildArchive(page, data, endpoint)
        animatePage(wrapper, page, dir, function(){
          respImg.load('.respImg')
        })
      })
    }
    else {
      if(endpoint === 'interviews'){
        const order = Cookies.get('ARCHIVEORDER') || 'abc'
        const url = `/wp-json/v1/interviews?order=${order}&count=${COUNT}&include=all`+cachebust(true)

        $.get(url, function(data){
          buildArchive(page, data, endpoint, true, [
            'Media',
            'No Media',
            'All'
          ])

          animatePage(wrapper, page, dir, function(){
            respImg.load('.respImg')
          })
        })

      } else {
        const url = '/wp-json/v1/'+endpoint+'?count='+COUNT+'&offset=0'+cachebust(true)
        $.get(url, function(data){
          buildArchive(page, data, endpoint, false, false)
          animatePage(wrapper, page, dir, function(){
            respImg.load('.respImg')
          })
        })
      }
    }
  } else {
    const url = `/wp-json/v1/${endpoint}/${queriedObject}`+cachebust()
    $.get(url, function(data){
      if(data.name) document.title = data.name
      DESCRIPTION = data.description
      if(endpoint === 'timelines'){
        buildTimelineHeader(page, data)
        buildTimeline(page, data.events, data.intro, () => {
          if(window.location.hash){
            const hash = window.location.hash
            setTimeout(function(){
              $('body, html').scrollTop($(hash).offset().top)
            }, TRANSITIONTIME)
          }
        })
      }
      else if(endpoint === 'interviews'){
        if(data.no_media) {
          buildTimelineHeader(page, data, 'Interview')
          buildTranscript(page, data.id, (transcript) => {
            socialHighlight('.transcript')
            buildSupp(page, endpoint, queriedObject, () => {
              if(data.collections.length) {
                buildOtherInCollection(page, data.id, data.collections[0])
              }
            })
          })
        }
        else {
          buildInterviewsHeader(page, data)
          buildTranscript(page, data.id, (transcript) => {
            socialHighlight('.transcript')
            buildSupp(page, endpoint, queriedObject, (supp) => {
              if(data.collections.length){
                buildOtherInCollection(page, data.id, data.collections[0])
              }
              syncAblePlayer(transcript, data.id, supp)
            }, !!transcript)
            stickyHeader(page, '.contentHeaderOuter', '.contentHeader-inner')
          })
        }
      }
      else if(endpoint === 'interactives') {
        buildTimelineHeader(page, data, false)
        buildTranscript(page, data.id, (transcript) => {
          socialHighlight('.transcript')
          buildSupp(page, endpoint, queriedObject, null, !!transcript)
        })
      }
      else if(endpoint === 'collections') {
        buildCollectionHeader(page, data)
        buildCollectionFeed(page, data)
      }

      animatePage(wrapper, page, dir, () => {
        if(endpoint === 'timelines' && $('.respImg').length < 1){
          buildSupp(page, endpoint, queriedObject, () => {
            if(data.collections.length){
              buildOtherInCollection(page, data.id, data.collections[0])
            }
          }, true)
          return
        }

        respImg.load('.respImg', () => {
          // run this as a callback so that height can be based on returned images
          if(endpoint === 'timelines'){
            buildSupp(page, endpoint, queriedObject, () => {
              if(data.collections.length){
                buildOtherInCollection(page, data.id, data.collections[0])
              }
            }, true)
          }
        })
        eqHeight('.others-single')
      })

    })
  }

}

module.exports = buildPage
