var highlightSuppCont = (nodes, highlight) => {
  $(nodes).each(function(){
    const text = $(this).attr('data-suppcont')
    const html = $(this).html()

    if(!highlight){
      $(this).text(text)
      return
    }

    console.log(text)

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
  })

}

module.exports = highlightSuppCont
