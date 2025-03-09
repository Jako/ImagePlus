const gulp = require('gulp'),
    autoprefixer = require('autoprefixer'),
    composer = require('gulp-uglify/composer'),
    concat = require('gulp-concat'),
    cssnano = require('cssnano'),
    footer = require('gulp-footer'),
    format = require('date-format'),
    header = require('@fomantic/gulp-header'),
    postcss = require('gulp-postcss'),
    rename = require('gulp-rename'),
    replace = require('gulp-replace'),
    sass = require('gulp-sass')(require('sass')),
    uglifyjs = require('uglify-js'),
    uglify = composer(uglifyjs, console),
    pkg = require('./_build/config.json');

const banner = '/*!\n' +
    ' * <%= pkg.name %> - <%= pkg.description %>\n' +
    ' * Version: <%= pkg.version %>\n' +
    ' * Build date: ' + format("yyyy-MM-dd", new Date()) + '\n' +
    ' */';
const year = new Date().getFullYear();

let phpversion;
let modxversion;
pkg.dependencies.forEach(function (dependency, index) {
    switch (pkg.dependencies[index].name) {
        case 'php':
            phpversion = pkg.dependencies[index].version.replace(/>=/, '');
            break;
        case 'modx':
            modxversion = pkg.dependencies[index].version.replace(/>=/, '');
            break;
    }
});

const scriptsMgr = function () {
    return gulp.src([
        'source/js/mgr/imageplus.js',
        'source/js/mgr/imageplus.panel.input.js',
        'source/js/mgr/imageplus.window.editor.js',
        'source/js/mgr/imageplus.migx_renderer.js',
        'source/js/mgr/tools/JSON2.js',
        'node_modules/jquery/dist/jquery.slim.min.js',
        'source/vendor/jcrop/js/jquery.Jcrop.min.js',
        'source/js/mgr/imageplus.jquery.imagecrop.js',
        'source/js/mgr/imageplus.grid.js'
    ])
        .pipe(concat('imageplus.min.js'))
        .pipe(uglify())
        .pipe(header(banner + '\n', {pkg: pkg}))
        .pipe(gulp.dest('assets/components/imageplus/js/mgr/'))
};
gulp.task('scripts', gulp.series(scriptsMgr));

const sassMgr = function () {
    return gulp.src([
        'source/sass/mgr/imageplus.scss'
    ])
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss([
            autoprefixer()
        ]))
        .pipe(gulp.dest('source/css/mgr/'))
        .pipe(concat('imageplus.css'))
        .pipe(postcss([
            cssnano({
                preset: ['default', {
                    discardComments: {
                        removeAll: true
                    }
                }]
            })
        ]))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(footer('\n' + banner, {pkg: pkg}))
        .pipe(gulp.dest('assets/components/imageplus/css/mgr/'))
};
gulp.task('sass', gulp.series(sassMgr));

const imagesMgr = function () {
    return gulp.src('./source/img/**/*.+(png|jpg|gif|svg)', {encoding: false})
        .pipe(gulp.dest('assets/components/imageplus/img/'));
};
gulp.task('images', gulp.series(imagesMgr));

const bumpCopyright = function () {
    return gulp.src([
        'core/components/imageplus/model/imageplus/imageplus.class.php',
        'core/components/imageplus/src/ImagePlus.php'
    ], {base: './'})
        .pipe(replace(/Copyright 2015(-\d{4})? by/g, 'Copyright ' + (year > 2015 ? '2015-' : '') + year + ' by'))
        .pipe(replace(/(@copyright .*?) 2015(-\d{4})?/g, '$1 ' + (year > 2015 ? '2015-' : '') + year))
        .pipe(gulp.dest('.'));
};
const bumpVersion = function () {
    return gulp.src([
        'core/components/imageplus/src/ImagePlus.php'
    ], {base: './'})
        .pipe(replace(/version = '\d+\.\d+\.\d+-?[0-9a-z]*'/ig, 'version = \'' + pkg.version + '\''))
        .pipe(gulp.dest('.'));
};
const bumpOptions = function () {
    return gulp.src([
        'core/components/imageplus/elements/tv/input/tpl/imageplus.options.tpl',
        'core/components/imageplus/elements/tv/output/tpl/imageplus.options.tpl'
    ], {base: './'})
        .pipe(replace(/&copy; 2015(-\d{4})?/g, '&copy; ' + (year > 2015 ? '2015-' : '') + year))
        .pipe(gulp.dest('.'));
};
const bumpDocs = function () {
    return gulp.src([
        'mkdocs.yml',
    ], {base: './'})
        .pipe(replace(/&copy; 2015(-\d{4})?/g, '&copy; ' + (year > 2015 ? '2015-' : '') + year))
        .pipe(gulp.dest('.'));
};
const bumpRequirements = function () {
    return gulp.src([
        'docs/index.md',
    ], {base: './'})
        .pipe(replace(/[*-] MODX Revolution \d.\d.*/g, '* MODX Revolution ' + modxversion + '+'))
        .pipe(replace(/[*-] PHP (v)?\d.\d.*/g, '* PHP ' + phpversion + '+'))
        .pipe(gulp.dest('.'));
};
gulp.task('bump', gulp.series(bumpCopyright, bumpVersion, bumpOptions, bumpDocs, bumpRequirements));

gulp.task('watch', function () {
    // Watch .js files
    gulp.watch(['./source/js/**/*.js'], gulp.series('scripts'));
    // Watch .scss files
    gulp.watch(['./source/sass/**/*.scss'], gulp.series('sass'));
    // Watch *.(png|jpg|gif|svg) files
    gulp.watch(['./source/img/**/*.(png|jpg|gif|svg)'], gulp.series('images'));
});

// Default Task
gulp.task('default', gulp.series('bump', 'scripts', 'sass', 'images'));
