const buildCollectionsList = require('./buildCollectionsList')
const buildConnected = require('./buildConnected')
const icon = require('./icon')
const scrollIndicator = require('./scrollIndicator')
const sharer = require('./sharer')

const buildInterviewsHeader = (
  page,
  {
    id,
    video_id,
    name,
    collections,
    introduction,
    related,
    transcript_url,
    description_url,
    link
  }
) => {
  const shareLinks = sharer(link, name, introduction.replace(/(<([^>]+)>)/ig,''), {})
  const indicator = scrollIndicator('.transcript')

  const append = `
    <div class="contentHeaderOuter">
      <header class="contentHeader contentHeader--interview">
        <span class="contentHeader-type">${icon('interview', 'type')}Interview</span>
        <div class="contentHeader-inner">
          <h2 class="contentHeader-head">${name}</h2>
          ${collections ? buildCollectionsList(collections) : ''}
          ${introduction ? `<div class="contentHeader-introduction">${introduction}</div>` : ''}
          ${related ? `
            <h3 class="contentHeader-relatedHead">Related to</h3>
            ${buildConnected(related)}
          ` : ''}
          <span class="contentHeader-selectWrapper" id="selectWrap-${id}" style="display: none;">
            <select class="contentHeader-select" id="select-${id}">
              <option value="null">Contents</option>
            </select>
          </span>
        </div>
        <div class="contentHeader-imgWrapper">
          <span class="contentHeader-toggleVid" data-action="toggle" data-target=".contentHeader-imgWrapper">
            <label for="toggleVid">Video Display:</label>
            <select id="toggleVid">
              <option>Small</option>
              <option selected>Medium</option>
              <option>Large</option>
              <option>Hidden</option>
            </select>
          </span>
          <video
            data-able-player
            data-youtube-id="${video_id}"
            data-youtube-playsinline
            ${transcript_url ? `data-transcript-src="transcript-${id}"` : ''}
          >
            ${transcript_url ? `<track kind="captions" src="${transcript_url}">` : ''}
            ${description_url ? `<track kind="descriptions" src="${description_url}">` : ''}
          </video>
          <a class="able-fake-pause"></a>
          <div class="contentHeader-searchwrap">
            <input class="contentHeader-search" id="video-search" placeholder="Search transcript, annotations & descriptions">
          </div>
          <div class="shareLinks">
            Share this Interview
            ${shareLinks.render}
          </div>
        </div>
        ${indicator.render}
      </header>
    </div>
  `

  console.log(append)

  page.append(append)
  shareLinks.attachHandlers()
  indicator.attachHandlers()
}

module.exports = buildInterviewsHeader
