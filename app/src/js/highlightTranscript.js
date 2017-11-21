var highlightTranscript = (transcript, selector, highlight) => {
  const nodes = transcript.find(selector)

  $(nodes).each(function(){
    const text = $(this).attr('data-node')
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
  })

}

module.exports = highlightTranscript
