# This is the public repo for the InSite interactive transcript project.

This repository contains a WordPress theme that contains the template files for the project, library code to make the back-end functionality work, and API endpoint setup.

## Getting Started

### Dependencies

This theme requires the [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/) plugin to work. All fields used by the site are instantiated in various places in the theme.

The theme also requires [Posts2Posts](https://wordpress.org/plugins/posts-to-posts/) to create relationships between pieces of content.

### Integrations
First, you'll need to create an application through the [Google Developers' Console](https://console.developers.google.com). This is necessary to for YouTube integration and Google Maps functionality.

Create a set of credentials. Select 'Oauth Client ID' and 'Web application.' Leave the authorized Javascript origins field blank, but add `[YOUR SITE URL]/wp-admin/` to Authorized Redirect URIs.

Copy and paste your `Client ID` and `Client Secret` into the appropriate fields on the Options page of the WordPress dashboard.

Create a separate set of credentials for Google Maps, and paste your Client ID into the appropriate field.

For Facebook sharing functionality (which uses Facebook's SDK) to work, you'll need to set up an application through Facebook and provide the client ID. This is optional for site functionality.

### Building
Building assets requires npm or yarn to be installed locally. To compile production ready assets, run `npm run build:js`, `npm run build:css`, or just `npm run build` to compile production-ready assets. (Note that the package.json file includes scripts for deployment as well, which relies on having a separate repo which can be integrated with whatever CI/CD tool you want. This project uses [DeployHQ](https://www.deployhq.com/) for to push to a number of production and staging servers).

## Structural overview
Front-end assets for the project are located in the `assets` directory. See **Building** for instructions on compiling assets. The interactive pages of the site are rendered with jQuery, using ES6 template strings to render page content to mimic a single-page application experience.

The site uses a name of API endpoints in order to expose data to the front-end. API routes are located in the `api` directory. Files are organized with one route per file, with naming and directory conventions based on route parameters. API routes use data models, with some additional data lookups depending on use-case. API response shapes are largely based around data shapes required by site's UI and do not necessarily correspond to REST conventions.

Data models are located in the `models` directory. This includes utility classes for accessing data, as well as the custom post/taxonomy and ACF field registrations.

Miscellaneous PHP helper functions are located in the `lib` directory. These functions range from things like registering menus or custom fields within WordPress, to providing regex for parsing transcripts.

## Child themes
Static content for the site (the front page, header, footer, Posts, and Pages) can be overwritten using [standard practices](https://codex.wordpress.org/Child_Themes)

However, because interactive content (interviews, collections, search results, etc) are rendered client-side from JS and CSS compiled with build tools from ES2017 and SCSS, these areas cannot be overwritten with child themes. You can fork this project and make any necessary changes.

If you'd just like to make simple CSS changes, a great place to start would be through a custom CSS plugin [like this one](https://wordpress.org/plugins/simple-custom-css/).

## Blog
The theme has support for a blog. In order to use this, add a Page assigned to the "Blog" template.

The blog also includes author archive pages. In order to use this, add a Page assigned to the "Authors" template. (Note that if you use Yoast SEO, you must manually enable author archives, which are turned off by default. You can get to this setting from SEO > Titles & Meta > Archives.)

## Documentation
Documentation for the project is a work in progress. If you have any questions about a particular piece of functionality, open an issue.

#### Guides
* [Adding supporting content as VTT](https://github.com/DukeLivingHistory/RLH-Site-Code/blob/master/docs/editing/EXAMPLE_SUPPORTING_CONTENT_VTT.MD)

## Thanks
This project is made possible due to a number of open source products.
* [Able Player](https://github.com/ableplayer/ableplayer)
* [Featherlight](https://noelboss.github.io/featherlight/)
* [Advanced Custom Fields](https://www.advancedcustomfields.com/)
* [js-cookie](https://github.com/js-cookie/js-cookie)
