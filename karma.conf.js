// Karma configuration
// Generated on Sun Nov 13 2016 15:51:21 GMT+0100 (CET)
var istanbul = require('browserify-istanbul');
module.exports = function(config) {
  config.set({

    // base path that will be used to resolve all patterns (eg. files, exclude)
    basePath: '',


    // frameworks to use
    // available frameworks: https://npmjs.org/browse/keyword/karma-adapter
    frameworks: ['mocha', 'browserify', 'jquery-2.1.0', 'chai'],


    // list of files / patterns to load in the browser
    files: [
      'webroot/js/**/*.js',
      'tests/Javascript/*_test.js',
      'tests/Javascript/test_*.js'
      
    ],


    // list of files to exclude
    exclude: ['webroot/js/bower_components/**/*.js', 'webroot/js/browser/**/*.js'],


    // preprocess matching files before serving them to the browser
    // available preprocessors: https://npmjs.org/browse/keyword/karma-preprocessor
    preprocessors: {        
        "webroot/js/**/*.js": [ "browserify"],
        'tests/Javascript/test_*.js':['browserify']
    },   
    
//    babelPreprocessor: {
//      options: {
//        presets: ['es2015']
//      },
//      filename: function (file) {
//        return file.originalPath.replace(/\.js$/, '.es5.js');
//      },
//      blacklist: ['useStrict']
//    }
//    ,
    browserify: {        
//        transform:[['babelify', {
//              ignore: '**/node_modules/'
//            }],
//            istanbul({
//              ignore: ['test/**', '**/node_modules/**']
//            })
//          ]
        transform: ["browserify-istanbul"]
    },
    
    reporters: ['dots', 'junit', 'coverage'],    
    
    junitReporter: {
      outputDir: 'build/results/',
      outputFile: 'junit-javascript.xml'
    },

    coverageReporter: {
        reporters: [
          {type: "html", dir:"build/results/"},
          {type: 'cobertura', dir:"build/results/", subdir: '.', file: 'cobertura.xml' },
          {type: "text-summary"}
        ]
    },
    // web server port
    port: 9876,


    // enable / disable colors in the output (reporters and logs)
    colors: true,


    // level of logging
    // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
    logLevel: config.LOG_INFO,


    // enable / disable watching file and executing tests whenever any file changes
    autoWatch: true,


    // start these browsers
    // available browser launchers: https://npmjs.org/browse/keyword/karma-launcher
    browsers: ['PhantomJS'],


    // Continuous Integration mode
    // if true, Karma captures browsers, runs the tests and exits
    singleRun: true,

    // Concurrency level
    // how many browser should be started simultaneous
    concurrency: Infinity
  });
};
