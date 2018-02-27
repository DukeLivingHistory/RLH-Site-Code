const cutoff = (elem) => {
  const alt = $(elem).data('alt')
  const orig = $(elem).text()

  $(elem).click(function(e){
    const $this = $(this)
    const $target = $this.siblings($this.data('cutoff'))
    $this.data('on', !$this.data('on'))
    $this.data('orig', $this.data('orig') || $this.text())

    if($this.data('on')) {
      $target.show()
      console.log('on')
      $target.children(':first').focus()
      $this.text($this.data('alttext'))
    }
    else {
      $target.hide()
      $this.text($this.data('orig'))
    }
  })
}

module.exports = cutoff
