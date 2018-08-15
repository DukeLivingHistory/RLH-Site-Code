const sublink = (elem) => {
  const handleOn = function() {
    const $link = $(this).closest('a')
    const sublink = $(this).attr('data-sublink')
    const href = $link.data('href') || $link.attr('href') // Get original href
    $link.data('href', href) // Cache original href
    $link.attr('href', `${href}#${sublink}`) // Set new href
  }

  const handleOff = function() {
    const $link = $(this).closest('a')
    $link.attr('href', $link.data('href')) // Restore original href
  }

  const pseudoClick = function(e) {
    if(e.which !== 13) return
    $(this).closest('a').trigger('click')
  }


  $(elem).hover(handleOn, handleOff)
    .focus(handleOn)
    .blur(handleOff)
    .keypress(pseudoClick)
}

module.exports = sublink
