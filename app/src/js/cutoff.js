const cutoff = (elem, all = null) => {
  const alt = $(elem).data('alt')
  const orig = $(elem).text()

  const handleChange = function(on = null) {
    const $this = $(this)
    const $target = $this.siblings($this.data('cutoff'))
    $this.data('orig', $this.data('orig') || $this.html())

    if(on !== null) {
      $this.data('on', on)
    } else {
      $this.data('on', !$this.data('on'))
    }

    if($this.data('on')) {
      $target.show()
      $this.html($this.data('alttext'))
      if(on === null) {
        $target.children(':first').focus()
        $this.siblings().hover(() => { $(':focus').blur() })
      }
    }
    else {
      $target.hide()
      $this.html($this.data('orig'))
      if(on === null) {
        $this.siblings().unbind('hover')
      }
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
        handleChange.bind(this)($this.data('on'))
      })
    })
  }
}

module.exports = cutoff
