'use strict';
var gulp         = require('gulp'),
    concat       = require('gulp-concat'),
    uglify       = require('gulp-uglify'),
    autoprefixer = require('gulp-autoprefixer'),
    sass         = require('gulp-sass'),
    minifyCss    = require('gulp-minify-css'),
    del          = require('del'),
    imagemin     = require('gulp-imagemin'),
    sourcemaps   = require('gulp-sourcemaps'),
    babel        = require('gulp-babel'),
    browserSync  = require('browser-sync').create(),
    util         = require('gulp-util');

gulp.task('clean', function () {
    del(['sass', 'js']);
});

gulp.task('sass', function () {
    return gulp.src([
        'web-src/scss/*.scss'
    ])
        .pipe(sourcemaps.init(''))
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(minifyCss({
            keepSpecialComments: 0
        }))
        .pipe(sourcemaps.write(''))
        .pipe(gulp.dest('web/css'))
        .pipe(browserSync.stream());
});

gulp.task('js', function () {
    return gulp.src([
            'bower_components/jquery/dist/jquery.min.js',
            'bower_components/typeahead.js/dist/typeahead.bundle.min.js'
        ])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('web/js/'));
});

gulp.task('fonts', function () {
    return gulp.src([
            'web-src/fonts/*'
        ])
        .pipe(gulp.dest('web/fonts/'))
});


gulp.task('default', ['clean'], function() {
    gulp.start('fonts', 'sass', 'js');
});

gulp.task('watch', ['default'], function () {
    var sass = gulp.watch('web-src/scss/*.scss', ['sass']);
});
