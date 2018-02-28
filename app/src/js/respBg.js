const cachebust = require('./cachebust')

const respBg = function(elem){
  const id = $(elem).attr('data-id')
  const set = $(elem).attr('data-set')

  const getSize = function(){
    const w = $(window).width()
    if(w >= 1200) return 'lg'
    if(w >= 992) return 'md'
    if(w >= 768) return 'sm'
    return 'xs'
  }

  const url = `/wp-json/v1/images/${id}/${set}_${getSize()}${cachebust()}`
  console.log(url)

  $.get(url, (data) => {
    console.log(data)
    $(elem).css('background-image', 'url('+data.requested+')')
  })

}

module.exports = respBg
