const cutoff = (elem) => {
  const alt = $(elem).data('alt')
  const orig = $(elem).text()

  $(elem).click(function(e){
    const $this = $(this)
    const $target = $this.siblings($this.data('cutoff'))
    $this.data('on', !$this.data('on'))
    $this.data('orig', $this.data('orig') || $this.html())

    if($this.data('on')) {
      $target.show()
      $target.children(':first').focus()
      $this.siblings().hover(() => { $(':focus').blur() })
      $this.html($this.data('alttext'))
    }
    else {
      $target.hide()
      $this.html($this.data('orig'))
      $this.siblings().unbind('hover')
    }
  })
}

module.exports = cutoff
