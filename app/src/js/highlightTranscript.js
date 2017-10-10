var highlightTranscript = (transcript, selector, highlight) => {
  if(!text) return
  const nodes = transcript.find(selector)

  $(nodes).each(function(){
    const text = $(this).text()

    const replaced = text.replace(
      new RegExp(`(${highlight})`, 'ig'),
      '<span class="transcript-highlight">$1</span>'
    )

    // prevent unnecessary DOM mutations
    if(replaced === text) return

    $(this).html(replaced)
  })

}

module.exports = highlightTranscript
