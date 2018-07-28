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

## Thanks
* [Able Player](https://github.com/ableplayer/ableplayer)
