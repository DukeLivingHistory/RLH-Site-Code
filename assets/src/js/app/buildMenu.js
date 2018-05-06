const icon = require('./icon')

const buildMenu = (page, items) => {
  const reducedItems = items.reduce((all, one) => {
    if(one.menu_item_parent > 0) {
      return all.map((parent) => {
        console.log(parent, one.menu_item_parent)
        if(parent.ID == one.menu_item_parent) {
          return Object.assign(parent, {
            children: parent.children ? parent.children.push(one) : [one]
          })
        }
        return parent
      })
    }
    return [...all, one]
  }, [])

  const menu = `
  <aside class="researchMenu">
    <button class="researchMenu-toggle">Expand Menu ${icon('down', 'link')}</button>
    <ul class="menu menu--research">
      ${reducedItems.map(item => `
        <li>
          <a href="${item.url}">${item.title}</a>
          ${item.children ? `
              <ul>
                ${item.children.map(child => `
                  <li><a href="${child.url}">${child.title}</a></li>
                `).join('')}
              </ul>
            ` : ''}
        </li>
      `).join('')}
    </ul>
  </aside>
  `
  console.log(menu)
  page.append(menu)
}

module.exports = buildMenu
