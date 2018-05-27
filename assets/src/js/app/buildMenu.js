const icon = require('./icon')

const buildMenu = (page, items) => {
  console.log(items)
  const reducedItems = items.reduce((all, one) => {
    if(one.menu_item_parent > 0) {
      return all.map((parent) => {
        if(parent.ID == one.menu_item_parent) {
          return Object.assign(parent, {
            child_items: parent.child_items ? [...parent.child_items, one] : [one]
          })
        }
        return parent
      })
    }
    return [...all, one]
  }, [])

  console.log(reducedItems)

  const menu = `
  <aside class="researchMenu researchMenu--app">
    <button class="researchMenu-toggle">Expand Menu ${icon('down', 'link')}</button>
    <ul class="menu menu--research">
      ${reducedItems.map(item => `
        <li>
          <a href="${item.url}">${item.title}</a>
          ${item.child_items ? `
              <ul>
                ${item.child_items.map(child => `
                  <li><a href="${child.url}">${child.title}</a></li>
                `).join('')}
              </ul>
            ` : ''}
        </li>
      `).join('')}
    </ul>
  </aside>
  `
  page.append(menu)
}

module.exports = buildMenu
