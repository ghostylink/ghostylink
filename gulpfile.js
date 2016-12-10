//var gulp = require('gulp'),
//    browserify = require('browserify'),
//    factor = require('factor-bundle');
//    globify = require('globify');
//var fs = require('fs');
//
//gulp.task('build', function() {    
////    globify(['webroot/js/**/*.js',
////            '--exclude', 'webroot/js/bower_components/**/*.js',
////            '-o', 'bundle/*.js',
////            '-p', 'factor-bundle', 'webroot/js/**/*.js', '-o', 'bundle/common2.js']);
////    return;
////    
//    var files = [ 'webroot/js/libs/encryptor.js', 'webroot/js/Users/edit.js' ];
//    var b = browserify(files);
//    b.plugin('factor-bundle', {outputs:['bundle/encryptor.js', 'bundle/edit.js']});
//    b.bundle().pipe(fs.createWriteStream('bundle/common.js'));
//});
var reduce = require('gulp-watchify-factor-bundle')
var gulp = require('gulp')
var path = require('path')
var buffer = require('vinyl-buffer')
//var uglify = require('gulp-uglify')
var del = require('del')
 
gulp.task('clean', function () {
  return del('bundle')
})
 
gulp.task('build', ['clean'], function () {
  var basedir = path.join(__dirname, 'webroot/js/')
  console.log(basedir);
  // Create a browserify instance 
  // same with `browserify(opts)` 
  var b = reduce.create({ basedir: basedir })
 
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
    .pipe(reduce.dest('bundle'))
})