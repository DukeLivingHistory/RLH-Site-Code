const escapeRegex = require('escape-string-regexp')

const highlightTranscript = (transcript, selector, highlight) => {
  highlight = highlight && escapeRegex(highlight)
  const nodes = transcript.find(selector)

  console.log(nodes, highlight)

  $(nodes).each(function(){
    const text = $(this).attr('data-node')
    const html = $(this).html()
      console.log(html)

    if(!highlight){
      $(this).html(text)
      return
    }

    const replaced = text.replace(
      new RegExp(`(${highlight})`, 'ig'),
      '<span class="transcript-highlight">$1</span>'
    )

    // prevent unnecessary DOM mutations
    if(replaced === text){
      $(this).html(text)
      return
    }

    if(replaced === html){
      return
    }

    $(this).html(replaced)
  })

}

module.exports = highlightTranscript
