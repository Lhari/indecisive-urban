var
	gulp 		= require('gulp'),
    concat      = require('gulp-concat'),
    sass        = require('gulp-sass'),
    uglify      = require('gulp-uglify'),
    watch       = require('gulp-watch'),
    prefix      = require('gulp-autoprefixer')
;


gulp.task('css', function() {
  gulp.src('source/styles/**.scss')
    .pipe(sass({ errLogToConsole: true }))
    .pipe(prefix("last 3 versions", "> .5%", "ie9")
      .on('error', function (error) { console.warn(error.message); }))
    .pipe(gulp.dest('css/'))
});


gulp.task('watch', function() {
      gulp.watch('source/styles/*/**', ['css']);
})

gulp.task('default', ['css', 'watch'], function() {});