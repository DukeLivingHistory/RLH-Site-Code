/**
 * config.js
 *
 */
'use strict';

import pkg from '../package.json';

let dest = './assets/';
let src = `${dest}/src`;
let assets = `${dest}/dist`

const config = {
    pkg: pkg,
    dest: dest,
    src: src,
    assets: assets
};

// The following extensions of config should
// have corresponsing gulp tasks

config.serve = {
    options: {
        server: dest,
        // or...
        // proxy: 'local.yourapp.com',
        open: true,
        port: 8888,
        https: false,
        notify: false,
        logLevel: 'info',
        reloadOnRestart: true,
        logPrefix: `${pkg.name} - v${pkg.version}`,
        ui: {
            port: 8889
        }
    }
};

config.images = {
    srcRaster: `${src}/img/{./,**/}*.{jpg,jpeg,png,gif}`,
    srcSvg: `${src}/img/{./,**/|!symbols/}*.svg`,
    srcSymbols: `${src}/img/symbols/*.svg`,
    symbolsName: { basename: 'symbols', suffix: '.min' },
    dest: `${assets}/img`,
    imagemin: {
        raster: {
            optimizationLevel: 4,
            progressive: true,
            interlaced: true,
            pngquant: true
        },
        vector: {
            svgoPlugins: [
                // More options here: https://github.com/svg/svgo
                { removeViewBox: false },
                { removeUselessStrokeAndFill: false },
                { removeEmptyAttrs: false }
            ]
        }
    }
};

config.favicon = {
    src: `${src}/favicon/*`,
    dest: `${dest}`
};

config.templates = {
    dest: dest,
    srcCopy: [
        `${src}/templates/**/*.html`,
        `!${src}/templates/index.html`
    ],
    srcReplace: [
        `${src}/templates/index.html`
    ],
    replace: {
        patterns: [
            {
                match: 'version',
                replacement: pkg.version
            },
            // Uncomment if reading files
            // {
            //   match: 'symbols',
            //   replacement: fs.readFileSync(`${assets}/img/${config.images.symbolsName.basename}${config.images.symbolsName.suffix}.svg`, 'utf8')
            // }
        ]
    }
};

config.bump = {
    src: [
        './package.json'
    ]
};

config.css = {
    src: `${src}/scss/main.scss`,
    dest: `${assets}/css/`,
    basename: 'styles',
    watch: `${src}/scss/**/*`,
    autoprefixer: {
        browsers: [
            'last 2 version',
            'ie 10',
            'ios 6',
            'android 4'
        ]
    }
};

config.fonts = {
    src: `${src}/fonts/*`,
    dest: `${assets}/fonts`
};

config.js = {
    dest: `${assets}/js`,
    outputFilename: 'scripts.min.js',
    browserify: {
        entries: [
            `${src}/js/app.js`
        ],
        debug: true
    }
};

config.modernizr = {
    src: [
        `${src}/js/**/*.js`,
        `${src}/sass/**/*.scss`,
    ],
    dest: `${assets}/js`,
    options: {
        cache : false,
        uglify : false,
        // forced tests
        tests : [
            'touchevents',
            'flexbox'
        ],
        // Default settings: http://modernizr.com/download/
        "options" : [
            "setClasses",
            "addTest",
            "html5printshiv",
            "testProp",
            "fnBind"
        ],
    }
};

config.clean = {
    build: [
        `${dest}/**/*`,
        `!${dest}/.gitignore`,
    ]
};

config.size = {
    showFiles: true,
    gzip: true
};

config.stylestats = {
    type: 'json',
    outfile: true,
    config: {
        "published": true,
        "paths": true,
        "stylesheets": true,
        "styleElements": true,
        "size": true,
        "dataUriSize": true,
        "ratioOfDataUriSize": true,
        "gzippedSize": true,
        "simplicity": true,
        "rules": true,
        "selectors": true,
        "declarations": true,
        "averageOfIdentifier": true,
        "mostIdentifier": true,
        "mostIdentifierSelector": true,
        "averageOfCohesion": true,
        "lowestCohesion": true,
        "lowestCohesionSelector": true,
        "totalUniqueFontSizes": true,
        "uniqueFontSizes": true,
        "totalUniqueFontFamilies": true,
        "uniqueFontFamilies": true,
        "totalUniqueColors": true,
        "uniqueColors": true,
        "idSelectors": true,
        "universalSelectors": true,
        "unqualifiedAttributeSelectors": true,
        "javascriptSpecificSelectors": "[#\\.]js\\-", // .json
        "userSpecifiedSelectors": false,
        "importantKeywords": true,
        "floatProperties": true,
        "mediaQueries": true,
        "propertiesCount": 10,
        "requestOptions": {}
    }
};

module.exports = config;
