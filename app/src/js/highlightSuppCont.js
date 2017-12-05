var respImg = require( './respImg' )

var highlightSuppCont = (nodes, highlight) => {
  $(nodes).each(function(){
    const text = $(this).attr('data-suppcont')
    const html = $(this).html()

    if(!highlight){
      $(this).text(text)
      return
    }

    const replaced = text.replace(
      new RegExp(`(${highlight})`, 'ig'),
      '<span class="transcript-highlight">$1</span>'
    )

    // prevent unnecessary DOM mutations
    if(replaced === text){
      $(this).text(text)
      return
    }

    if(replaced === html){
      return
    }

    $(this).html(replaced)

    const target = $(this).closest('.suppCont-single')
    target.addClass('expand--search')
    target.find('[data-action="close"] use')
    .attr( 'xlink:href', '#contract')

    const img = target.find('.respImg-defer')
    respImg.load(img)
  })

}

module.exports = highlightSuppCont
