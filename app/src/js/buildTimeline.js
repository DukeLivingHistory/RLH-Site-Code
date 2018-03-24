const icon = require('./icon')
const respImg = require('./respImg')
const sharer = require('./sharer')

const buildTimeline = (
  page,
  events,
  intro,
  cb
) => {
  const shares = []

  const append = `
    <div class="content-intro">${intro}</div>
    <ul class="timeline">
      ${events.map(({
        event_date,
        title,
        image,
        content,
        content_link,
        content_link_type,
        content_link_id,
        content_link_text,
      }, index) => {
        const shareLinks = sharer(
          window.location.href.split('#')[0]+'#'+index,
          title,
          content.replace(/(<([^>]+)>)/ig,''),
          {}
        )

        shares.push({
          id: shareLinks.id,
          options: shareLinks.options
        })

        return `
          <li id="${index}" class="event loaded" data-start="${event_date}">
            <span class="event-dot"></span>
            <date class="event-date">${event_date}</date>
            <h3 class="event-head" data-node="<a href='#${index}'>${title}</a>"><a href="#${index}">${title}</a></h3>
            ${image ? `<div class="event-imageWrapper">
              ${respImg.markup(image, 'feat_lg', 'respImg', null, true)}
            </div>` : ''}
            ${content.length ?
              `<div class="event-content" data-node="${content}">${content}</div>` :
              ''
            }
            ${content_link ? `
              <a class="js-internalLink relatedItem relatedItem--${content_link_type}"
                data-type="${content_link_type}"
                data-id="${content_link_id}"
                href=${content_link}
              >
                ${icon(content_link_type, 'type')} ${content_link_text}
              </a>
            ` : ''}
            <div class="event-social">
              ${shareLinks.render}
            </div>
          </li>
        `
      }
    ).join(' ')}
    </ul>
  `

  page.append(append)
  shares.forEach(({id, options}) => { sharer().attachHandlers(id, options) })
  if(cb) cb(page)
}

module.exports = buildTimeline
