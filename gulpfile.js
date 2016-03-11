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
        .on('error', browserifyHandler)
        .pipe(autoprefixer())
        .pipe(minifyCss({
            keepSpecialComments: 0
        }))
        .pipe(sourcemaps.write(''))
        .pipe(gulp.dest('web/css'))
        .pipe(browserSync.stream());
});
//
//gulp.task('images', function () {
//    return gulp.src([
//        'web-src/images/*',
//        'bower_components/bootstrap-chosen/chosen-sprite.png',
//        'bower_components/bootstrap-chosen/chosen-sprite@2x.png'
//    ])
//        .pipe(imagemin({
//            progressive: true,
//            interlaced: true
//        }))
//        .pipe(gulp.dest('web/images/'))
//        .pipe(browserSync.stream());
//});
//
//gulp.task('scripts', function() {
//    return gulp.src(['web-src/js/**/*.js'])
//        .pipe(sourcemaps.init())
//        .pipe(babel())
//        .on('error', browserifyHandler)
//        .on('error', browserifyHandler)
//        .pipe(uglify())
//        .pipe(sourcemaps.write("."))
//        .pipe(gulp.dest('web/js'))
//        .pipe(browserSync.stream());
//});
//
//function browserifyHandler(err) {
//    util.log(util.colors.red('Error: ' + err.message));
//    this.end();
//}
//
//gulp.task('fonts', function () {
//    return gulp.src([
//            'bower_components/bootstrap-sass/assets/fonts/**/*',
//            'bower_components/font-awesome/fonts/*'
//        ])
//        .pipe(gulp.dest('web/fonts/'))
//});


gulp.task('js', function () {
    return gulp.src([
            'bower_components/jquery/dist/jquery.min.js',
            'bower_components/typeahead.js/dist/typeahead.bundle.min.js'
            //'bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js',
            //'bower_components/chosen/chosen.jquery.js',
            //'vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.js'
        ])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('web/js/'));
});

gulp.task('default'/*, ['clean']*/, function() {
    gulp.start(/*'fonts', 'sass', */'js'/*, 'images', 'scripts'*/);
});
//
//gulp.task('watch', ['default'], function () {
//    var sass = gulp.watch('web-src/scss/*.scss', ['sass']),
//        js = gulp.watch('web-src/js/**', ['scripts']);
//});
