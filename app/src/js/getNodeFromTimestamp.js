const getNodeFromTimestamp = () => {
  if(window.location.hash){
    const hash = window.location.hash
    const match_id = hash.match(/\#(\d*)/)
    if(match_id && match_id[1].length){
      if($('[data-start="'+match_id[1]+'"]').length){
        return $('[data-start="'+match_id[1]+'"]')
      }
    }
  }
  return false
}

module.exports = getNodeFromTimestamp
