// Karma configuration
// Generated on Sun Nov 13 2016 15:51:21 GMT+0100 (CET)

module.exports = function(config) {
  config.set({

    // base path that will be used to resolve all patterns (eg. files, exclude)
    basePath: '',


    // frameworks to use
    // available frameworks: https://npmjs.org/browse/keyword/karma-adapter
    frameworks: ['mocha', 'browserify', 'jquery-2.1.0', 'chai'],


    // list of files / patterns to load in the browser
    files: [
      'webroot/js/libs/**/*.js',
      'tests/Javascript/*_test.js',
      'tests/Javascript/test_*.js'
    ],


    // list of files to exclude
    exclude: ['webroot/js/bower_components'],


    // preprocess matching files before serving them to the browser
    // available preprocessors: https://npmjs.org/browse/keyword/karma-preprocessor
    preprocessors: {        
        "webroot/js/**/*.js": [ "browserify"],
        'tests/Javascript/test_*.js':['browserify']
    },   
    
    browserify: {
        debug: true,
        transform: ["browserify-istanbul"]
    },
    
    reporters: ['progress', 'coverage'],    
        
    coverageReporter: {
        reporters: [
          {type: "html"},
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
    browsers: ['Firefox'],


    // Continuous Integration mode
    // if true, Karma captures browsers, runs the tests and exits
    singleRun: false,

    // Concurrency level
    // how many browser should be started simultaneous
    concurrency: Infinity
  })
}
