const sublink = (elem) => {
  $(elem).hover(function() {
    const $link = $(this).closest('a')
    const sublink = $(this).attr('data-sublink')
    const href = $link.data('href') || $link.attr('href') // Get original href
    $link.data('href', href) // Cache original href
    $link.attr('href', `${href}#${sublink}`) // Set new href
  }, function() {
    const $link = $(this).closest('a')
    $link.attr('href', $link.data('href')) // Restore original href
  })
}

module.exports = sublink
