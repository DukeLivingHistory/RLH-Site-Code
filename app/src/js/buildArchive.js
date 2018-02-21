var cachebust = require('./cachebust')
var buildContentNode = require('./buildContentNode')
var icon             = require('./icon')
var respBg           = require('./respBg')
var Cookies          = require('js-cookie')

var buildArchive = function(
  page,
  data,
  endpoint,
  canBeCondensed,
  mediaTypes
){
  var header = $('<header class="contentHeader contentHeader--archive"/>')
  if(data.image) {
    var hero = $('<figure class="heroImg js-respBg" data-set="hero" data-id="'+data.image+'">')
  }
  var feed = $('<ul class="content-feed"/>')
  var load = $('<button class="content-load">Load More</button>')
  var isAbc = false

  header.append('<h2>'+decodeURI(data.name)+'</h2>')
  if(data.image){
    header.append(hero)
    respBg(hero)
  }

  if(data.items){
    data.items.forEach((item) => {
      feed.append(buildContentNode(item))
    })
  } else {
    feed.append('Sorry, no results were found.')
  }

  page.append(header)

  if(canBeCondensed){
    var btnCondense = $('<input type="radio" name="list-view" value="condense">')
    var btnExplode   = $('<input type="radio" name="list-view" value="explode">')

    if(Cookies.get('ARCHIVEVIEW') === 'condense'){
      btnCondense.attr('checked', 'checked')
      feed.addClass('content-feed--contracted')
    } else {
      btnExplode.attr('checked', 'checked')
    }

    btnCondense.click(function(){
      feed.addClass('content-feed--contracted')
    })

    btnExplode.click(function(){
      feed.removeClass('content-feed--contracted')
    })

    var viewSelect = $('<select name="list-order"/>')

    viewSelect.append('<option value="abc_asc">A-Z</option>')
    viewSelect.append('<option value="abc_desc">Z-A</option>')
    viewSelect.append('<option value="date_desc">Date Interviewed</option>')
    viewSelect.append('<option value="publish_desc">Date Published</option>')
    viewSelect.append('<option value="date_asc">Date Interviewed (reverse)</option>')
    viewSelect.append('<option value="publish_asc">Date Published (reverse)</option>')

    var archiveOrder = Cookies.get('ARCHIVEORDER')

    viewSelect.find('[value="'+archiveOrder+'"]').attr('selected', 'selected')

    var listView = $('<div class="listView"/>')
    listView
      .append('<span class="listView-label">change view:</span')
      .append(btnExplode)
      .append(icon('explode', 'listView'))
      .append(btnCondense)
      .append(icon('condense', 'listView'))
      .append('<span class="listView-label">sort by:</span>')
      .append(viewSelect)

    if(mediaTypes){
      var mediaSelect = $('<select name="media-type"/>')
      mediaTypes.forEach((type) => {
        mediaSelect.append(`<option value="${type.toLowerCase().replace(' ', '-')}">${type}</option>`)
      })
      listView
        .append('<span class="listView-label">content types:</span>')
        .append(mediaSelect)
    }

    page.append(listView)
  }

  page.append(feed)

  if(data.items && data.items.length >= COUNT){
    page.append(load)
  }

  load.data('offset', 0)
  load.click(function(){
    load.data('offset', load.data('offset') + 1)
    var dest = endpoint === 'search' ? endpoint+'/'+$('body').attr('data-search') : endpoint
    var params = ''

    var order = $('[name="list-order"]').val()

    params = '&order=' + order

    console.log(order)

    var url  = '/wp-json/v1/'+dest+'?count='+COUNT+'&offset=' + (load.data('offset') * COUNT)+cachebust(true) + params

    $.get(url, function(data){
      data.items.forEach((item) => {
        feed.append(buildContentNode(item))
      })

      if(data.items.length < COUNT){
        load.hide()
      }
    })
  })

  const handleListChange = function() {
    load.data('offset', 0)
    let media = ''
    const view  = $('input[name="list-view"]:checked').val()
    const order = $('select[name="list-order"]').val()
    if(mediaTypes) {
      media = $('select[name="media-type"]').val()
    }

    const dest = (endpoint === 'search') ? endpoint+'/'+$('body').attr('data-search') : endpoint
    const url  = `/wp-json/v1/${dest}?order=${order}&offset=0&count=${COUNT}&include=${media}`+cachebust(true)

    console.log(url)

    $.get(url, function(data){
      feed.empty()
      data.items.forEach((item) => {
        feed.append(buildContentNode(item))
      })

      if(data.items.length < COUNT){
        load.hide()
      } else {
        load.show()
      }
    })
  }

  if(listView){
    listView.find('input[name="list-view"]').click(handleListChange)
    listView.find('select[name="list-order"]').change(handleListChange)
  }

  if(mediaTypes) {
    listView.find('select[name="media-type"]').change(handleListChange)
  }

  $('body').on('click', '.content', function(e){
    var link             = $(this).find('.js-internalLink')
    var target           = link.attr('href')
    var _endpoint        = link.attr('data-type')
    var _queriedObject   = link.attr('data-id')
  })
}

module.exports = buildArchive
