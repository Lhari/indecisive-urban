var gulp  = require('gulp'),
    gutil = require('gulp-util'),
    sass = require('gulp-sass'),
    cssnano = require('gulp-cssnano'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    jshint = require('gulp-jshint'),
    stylish = require('jshint-stylish'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    plumber = require('gulp-plumber'),
    bower = require('gulp-bower'),
    babel = require('gulp-babel'),
    browserSync = require('browser-sync').create();


gulp.task('css', function() {
  gulp.src('source/styles/**.scss')
    .pipe(sass({
      errLogToConsole: true
    }))
    .pipe(prefix("last 3 versions", "> .5%", "ie9")
      .on('error', function(error) {
        console.warn(error.message);
      }))
    .pipe(gulp.dest('template/css/'))
});

gulp.task('fonts', function() {
  gulp.src('source/font/*')
    .pipe(gulp.dest('template/css/font'))
});

gulp.task('scripts', function() {
  return gulp.src('source/scripts/*.js')
    .pipe(plumber())
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'))
    .pipe(concat('main.js'))
    .pipe(gulp.dest('template/scripts'))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(uglify())
    .pipe(gulp.dest('template/scripts'))
});

gulp.task('watch', function() {
  gulp.watch('source/styles/*/**', ['css']);
})

gulp.task('default', ['css', 'watch'], function() {});
