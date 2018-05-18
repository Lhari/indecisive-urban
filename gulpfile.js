const gulp = require('gulp'),
	uglify = require('gulp-uglify'),
	concat = require('gulp-concat'),
	rename = require('gulp-rename'),
	prefix = require('gulp-autoprefixer'),
	sass = require('gulp-sass')

gulp.task('css', function () {
	gulp.src('source/styles/**.*css')
		.pipe(sass({
			errLogToConsole: true
		}))
		.pipe(prefix("last 3 versions", "> .5%", "ie9")
			.on('error', function (error) {
				console.warn(error.message);
			}))
		.pipe(gulp.dest('template/css/'))
});

gulp.task('fonts', function () {
	gulp.src('source/font/*')
		.pipe(gulp.dest('template/css/font'))
});

gulp.task('scripts', function () {
	return gulp.src('source/scripts/*.js')
		.pipe(concat('main.js'))
		.pipe(gulp.dest('template/scripts'))
		.pipe(rename({
			suffix: '.min'
		}))
		.pipe(uglify())
		.pipe(gulp.dest('template/scripts'))
});

gulp.task('root', function () {
	gulp.src('root/*.php').pipe(gulp.dest('..'));
});

gulp.task('watch', function () {
	gulp.watch('source/styles/*/**', ['css']);
	gulp.watch('source/scripts/*.js', ['scripts']);
	gulp.watch('source/font/*/**', ['fonts']);
	gulp.watch('root/*.php', ['root'])
});

gulp.task('default', ['css', 'scripts', 'fonts', 'root', 'watch'], function () {});
