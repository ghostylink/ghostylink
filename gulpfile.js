
var reduce = require('gulp-watchify-factor-bundle')
var gulp = require('gulp')
var path = require('path')
var buffer = require('vinyl-buffer')

//var uglify = require('gulp-uglify')
var del = require('del')
 
gulp.task('clean', function () {
  return del('webroot/js/browser');
})

gulp.task('build', ['clean'], function () {
  var basedir = path.join(__dirname, 'webroot/js/');  
  // Create a browserify instance 
  // same with `browserify(opts)` 
  var b = reduce.create({ basedir: basedir });  
  // find entries 
  // same with gulp.src() 
  return reduce.src(['**/*.js','!./bower_components/**'], { cwd: basedir })
    // apply `factor-bundle` 
    // and call b.bundle() which produces a vinyl stream now 
    .pipe(reduce.bundle(b, { common: 'common-libs.js' }))
 
    // apply gulp plugins to process the vinyl stream 
    .pipe(buffer())
//    .pipe(uglify())
 
    // same with gulp.dest 
    .pipe(reduce.dest('webroot/js/browser'))
})        