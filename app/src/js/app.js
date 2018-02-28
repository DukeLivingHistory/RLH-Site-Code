var buildPage               = require('./buildPage')
var destroyPage             = require('./destroyPage')
var eqHeight                = require('./eqHeight')

$(document).ready(function(){
  $('html').removeClass('no-js')

  window.HASPAGE = false // set to true upon first page build. this way we can check to see if we have a page before popping state
  window.TRANSITIONTIME = 500 // should match CSS values
  window.DESCRIPTION = '' // used in sharing links
  window.JUMPTOACTIVE = false // set ableplayer polling as a window-scoped object so it can be overwritten
  window.SEARCHTERM = '' // On searching, cache value for use later

  // Safari calls popstate on page load
  // and has its own forward/backward ui
  // So we need to avoid replicating this behavior
  window.IGNOREDIR = false

  window.IS_TOUCH_SCREEN = false
  window.addEventListener('touchstart', function _detectTouch() {
    window.IS_TOUCH_SCREEN = true
    window.removeEventListener('touchstart', _detectTouch, false)
  })

  var History         = window.history
  var wrapper         = $('.app-wrapper')
  var endpoint        = $('body').attr('data-endpoint')
  var queriedObject   = $('body').attr('data-id')

  // store navigation history as object
  var sequence = { prev: 0, curr: 0 }

  wrapper.empty()
  buildPage(wrapper, endpoint, queriedObject, false)

  $('body').on('click', '.js-internalLink', function(e){
    if($(e.target).attr('data-nolink')) {
      e.preventDefault()
      return false
    }

    HASPAGE = true
    if(IGNOREDIR) return
    e.preventDefault()
    var target           = $(this).attr('href')
    var _endpoint        = $(this).attr('data-type')
    var _queriedObject   = $(this).attr('data-id')

    $('html, body').animate({
      scrollTop: 0
    }, TRANSITIONTIME)

    destroyPage(wrapper, 'left')
    buildPage(wrapper, _endpoint, _queriedObject, 'right')

    sequence = { prev: sequence.curr, curr: sequence.curr + 1 } // update sequence sequentially

    History.pushState(
      { endpoint: _endpoint,
        queriedObject: _queriedObject,
        sequence: sequence // store the current sequence at this point in navigation history
      },
      null,
      target
    )
    return false
  })

  $(window).on('popstate', function(){
    if(!HASPAGE){
      IGNOREDIR = true
    }
    var _endpoint      = history.state ?  history.state.endpoint : endpoint
    var _queriedObject = history.state ?  history.state.queriedObject : queriedObject
    var _sequence      = history.state ?  history.state.sequence : false //sanity check

    if(IGNOREDIR) return
    if(_sequence){
      // active previous matches historical current
      // so we go backwards
      if(sequence.prev === _sequence.curr){
        destroyPage(wrapper, 'right')
        buildPage(wrapper, _endpoint, _queriedObject, 'left')
      }
      // active current matches historical previous
      // so we go forwards
      if(sequence.curr === _sequence.prev){
        destroyPage(wrapper, 'left')
        buildPage(wrapper, _endpoint, _queriedObject, 'right')
      }
      // update our place in history
      sequence = _sequence

    //default to backwards
    } else {
      destroyPage(wrapper, 'right')
      buildPage(wrapper, _endpoint, _queriedObject, 'left')
    }
  })

  eqHeight('.js-eqHeight')

  // Global Event Handlers
  $('body').on('click', () => {
    if(!window.SOCIAL_POPUP_IS_OPEN) return
    setTimeout(() => {
      $('.socialPopup').remove()
      window.SOCIAL_POPUP_IS_OPEN = false
    }, 500)
  })
})
