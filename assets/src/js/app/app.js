/**
 * This file is the entry point for our application. It bootstraps the build and destroy animations,
 * which animate the various pages in and out. Pages are rendered as template strings in various
 * files and appended to the page.
 */

// Set lightbox plugins as window-scoped variables so they can be accessed anywhere in application
window.featherlight = require( './thirdparty/featherlight.min' );
window.featherlightGallery = require( './thirdparty/featherlight.gallery.min.js' );

var buildPage   = require('./buildPage')
var destroyPage = require('./destroyPage')
var eqHeight    = require('./eqHeight')

$(document).ready(function(){
  $('html').removeClass('no-js')

  // Once we build a page, we'll set this to true. That way, if a browser pops state before
  // the initial build, we can avoid the initial animation.
  window.HASPAGE = false
  
  // Constant used for various animations
  window.TRANSITIONTIME = 500 // should match CSS values
  
  // When a page loads, this value is set based on the rendered content. It's used to
  // generate sharing links in multiple places in the application.
  window.DESCRIPTION = ''
  
  // On every AblePlayer tick, this updates based on if the currently active timestamp is in the viewport.
  window.JUMPTOACTIVE = false
  
  // When a user searches, we cache the search term so that we can display it in subsequent
  // renders.
  window.SEARCHTERM = ''

  // Safari calls popstate on page load and has its own forward/backward UI,
  // so we use this boolean to disable page animations until after the initial build.
  window.IGNOREDIR = false

  // Various pieces of application logic depend on if we're on a touch device or not,
  // so on the initial touch, we set a constant.
  window.IS_TOUCH_SCREEN = false
  window.addEventListener('touchstart', function _detectTouch() {
    window.IS_TOUCH_SCREEN = true
    window.removeEventListener('touchstart', _detectTouch, false)
  })

  // Initial variables for bootstrapping application
  var History         = window.history
  var wrapper         = $('.app-wrapper')
  var endpoint        = $('body').attr('data-endpoint')
  var queriedObject   = $('body').attr('data-id')

  // We memoize the current and previous 
  var sequence = { prev: 0, curr: 0 }

  wrapper.empty()
  
  // Initial page build
  buildPage(wrapper, endpoint, queriedObject, false)

  // This sets an event listener on .js-internalLink elements that renders an animated
  // page build, based on the type and id of the link
  $('body').on('click', '.js-internalLink', function(e){
    // If the target has [data-nolink] we want to kill the internal link navigation to provide an opt-out path
    if($(e.target).attr('data-nolink')) {
      e.preventDefault()
      return false
    }

    // Let global booleans know that we've rendered a page
    HASPAGE = true
    // If we haven't yet reset this boolean, return earlier before animation
    
    if(IGNOREDIR) return
    e.preventDefault()
    var target           = $(this).attr('href')
    var _endpoint        = $(this).attr('data-type')
    var _queriedObject   = $(this).attr('data-id')

    // Animation to top of the page
    $('html, body').animate({
      scrollTop: 0
    }, TRANSITIONTIME)

    // Destroy the previous page and build the next, based on the type and queried object of the clicked link
    destroyPage(wrapper, 'left')
    buildPage(wrapper, _endpoint, _queriedObject, 'right')

    // Create a new sequence pattern
    sequence = { prev: sequence.curr, curr: sequence.curr + 1 }

    // Pass the endpoint, type, and sequence pattern to browser history and pass the href of clicked link to URL
    History.pushState(
      { endpoint: _endpoint,
        queriedObject: _queriedObject,
        sequence: sequence
      },
      null,
      target
    )
    return false
  })

  // Whenever the window pops state, we have a navigation event. We compare the prev and curr values
  // of our sequence pattern to determine whether or not we need to animate pages in or out.
  $(window).on('popstate', function(){
    if(!HASPAGE){
      IGNOREDIR = true
    }
    var _endpoint      = history.state ? history.state.endpoint : endpoint
    var _queriedObject = history.state ? history.state.queriedObject : queriedObject
    var _sequence      = history.state ? history.state.sequence : false //sanity check

    if(IGNOREDIR) return
    if(_sequence){
      // Active previous matches historical current so we go backwards
      if(sequence.prev === _sequence.curr){
        destroyPage(wrapper, 'right')
        buildPage(wrapper, _endpoint, _queriedObject, 'left')
      }
      // Active current matches historical previous so we go forwards
      if(sequence.curr === _sequence.prev){
        destroyPage(wrapper, 'left')
        buildPage(wrapper, _endpoint, _queriedObject, 'right')
      }
      // Update our sequence pattern
      sequence = _sequence

    // Default to animating backwards
    } else {
      destroyPage(wrapper, 'right')
      buildPage(wrapper, _endpoint, _queriedObject, 'left')
    }
  })

  // Trigger function to make elements that need to be equal heights, equal heights.
  // NOTE: This can maybe be deprecated, as most of these elements have been replaced
  // by flexbox.
  eqHeight('.js-eqHeight')

  // Global Event Handlers
  $('body').on('mousedown touchstart', () => {
    // Whenever a user clicks, remove any .socialPopups after 200 ms
    setTimeout(() => {
      if(!window.SOCIAL_POPUP_SHOULD_BE_REMOVED) return
      $('.socialPopup').remove()
      window.SOCIAL_POPUP_SHOULD_BE_REMOVED = false
    }, 200)
  })
})
