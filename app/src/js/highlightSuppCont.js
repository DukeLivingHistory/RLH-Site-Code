var respImg = require( './respImg' )

var highlightSuppCont = (nodes, subnodes, highlight) => {
  const HIGHLIGHT = new RegExp(`(${highlight})`, 'ig')
  $(nodes).each(function(){
    const $subnodes = $(this).find(subnodes)
    let isMatchAll = false
    $subnodes.each(function(){
      const text = $(this).attr('data-suppcont')
      const isMatch = text.match(HIGHLIGHT)
      if(isMatch) {
        isMatchAll = true
        $(this).html(text.replace(
          new RegExp(`(${highlight})`, 'ig'),
      '<span class="transcript-highlight">$1</span>'
        ))
      } else {
        $(this).html(text)
      }
    })

    if(isMatchAll){
      $(this).addClass('expand')
      $(this).find('[data-action="close"] use').attr('xlink:href', '#contract')
      const $img = $(this).find('.respImg-defer')
      respImg.load($img)
    } else {
      $(this).removeClass('expand')
      $(this).find('[data-action="close"] use')
      .attr( 'xlink:href', '#expand')
    }
  })

}

module.exports = highlightSuppCont
