const buildAuthor = (page, {
  name,
  link,
  avatar,
  bio
}) => { if(name) page.append(`
<div class="author">
  <a href="${link}" class="author-link">
    ${avatar && avatar !== 'null' ? (
    `<div class="author-thumbnail">
      ${avatar}
    </div>`
  ) : null }
    <div class="author-bio">
      <strong>${name}</strong>
      <p>${bio}</p>
    </div>
  </a>
</div>
`) }

module.exports = buildAuthor
