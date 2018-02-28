const icon = require( './icon' );

const scrollIndicator = (ref) => {
  const render = `<div class="scrollIndicator">${icon('down', 'scrollIndicator')}</div>`

  const toggle = (elem) => {
    if(!$(elem).length) return
    if($(window).scrollTop() + $(window).height() > $(elem).offset().top + 33) {
      $('.scrollIndicator').addClass('scrollIndicator--hidden')
    }
    else {
      $('.scrollIndicator').removeClass('scrollIndicator--hidden')
    }
  }

  const attachHandlers = () => {
    toggle(ref)
    $(window).on('scroll resize', () => { toggle(ref) })
    $('body').unbind('click.scrollIndicator').on('click.scrollIndicator', '.scrollIndicator', () => {
      const scrollTop = $(ref).offset().top - 33
      $('body,html').animate({ scrollTop }, 500)
    })
  }

  return {
    render,
    attachHandlers: () => { attachHandlers() }
  }
}

module.exports = scrollIndicator
