const cutoff = (elem, all = null) => {
  const alt = $(elem).data('alt')
  const orig = $(elem).text()

  const handleChange = function() {
    const $this = $(this)
    const $target = $($this.data('cutoff'))
    $this.data('orig', $this.data('orig') || $this.html())

    $this.data('on', !$this.data('on'))

    if($this.data('on')) {
      $target.show()
      $this.html($this.data('alttext'))
    }
    else {
      $target.hide()
      $this.html($this.data('orig'))
    }
  }

  $(elem).click(function() {
    handleChange.bind(this)()
  })

  if(all) {
    $(all).click(function(e) {
      const $this = $(this)
      $this.data('on', !$this.data('on'))
      $this.data('orig', $this.data('orig') || $this.html())
      $this.html($this.data('on') ? $this.data('alttext') : $this.data('orig'))
      $(elem).each(function() {
        handleChange.bind(this)()
      })
    })
  }
}

module.exports = cutoff
