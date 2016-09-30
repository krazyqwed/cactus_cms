var gulp = require('gulp');

var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var cssnano = require('gulp-cssnano');

var babelify = require('babelify');
var browserify = require('browserify');
var buffer = require('vinyl-buffer');
var source = require('vinyl-source-stream');
var uglify = require('gulp-uglify');

var livereload = require('gulp-livereload');

gulp.task('sass_admin', function () {
  return gulp.src('./res/css/admin/sass/admin.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(cssnano())
    .pipe(gulp.dest('./res/css/admin'))
    .pipe(livereload());
});

gulp.task('sass_main', function () {
  return gulp.src('./res/css/main/sass/main.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer())
    .pipe(cssnano())
    .pipe(gulp.dest('./res/css/main'))
    .pipe(livereload());
});

gulp.task('js_main', function () {
  var bundler = browserify('./res/js/main/src/main.js');
  bundler.transform(babelify);

  return bundler.bundle()
    .on('error', function (err) { console.error(err); })
    .pipe(source('app.js'))
    .pipe(buffer())
    .pipe(uglify({
      compress: {
        dead_code     : true,
        drop_debugger : true,
        global_defs   : {
          'DEBUG': false
        }
      }
    }))
    .pipe(gulp.dest('./res/js/main'))
    .pipe(livereload());
});

gulp.task('watch',function() {
  livereload.listen();

  gulp.watch('./res/css/admin/sass/**/*.scss', ['sass_admin']);
  gulp.watch('./res/css/main/sass/**/*.scss', ['sass_main']);
  gulp.watch('./res/js/main/src/**/*.js', ['js_main']);
});

gulp.task('default', ['sass_admin', 'sass_main', 'js_main'], function() {
  return;
});
