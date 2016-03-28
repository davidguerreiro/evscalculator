module.exports = function(grunt) {

  var the_tasks = [
    'uglify', 
    'sass',
    'cssmin',
  ];

  var the_tasks_watch = Array.prototype.slice.call(the_tasks, 0);
  the_tasks_watch.push('watch');

  grunt.initConfig({
    uglify: {
      options: {
      },
      dist: {
        files: {
          // Fonts
          'js/fonts.min.js': [
            'src/js/vendor/FontFaceObserver.js', 
            'src/js/fonts.js'
          ],
          // Critical JS: Enhance.js
          'templates/includes/critical.min.js': [
            'src/js/critical.js'
          ],
          'js/async.min.js': [
            'src/js/vendor/d3.js',
            'src/js/components/StatGraph.js',
            'src/js/components/StatValidator.js',
            'src/js/async.js'
          ]
        }
      },
    },

    sass: {
      dist: {
        options: {
          style: 'expanded',
          sourcemap: 'none'
        },
        files: {
          'templates/includes/critical.min.css': 'src/scss/critical.scss',
          'css/full.css': 'src/scss/full.scss',
          'css/ie.css': 'src/scss/ie.scss',
          'css/nojs.css': 'src/scss/nojs.scss'
        }
      }
    },

    cssmin: {
      options: {
      },
      dist: {
        files: {
          'templates/includes/critical.min.css': ['templates/includes/critical.min.css'],
          'css/full.css': ['css/full.css'],
          'css/nojs.css': ['css/nojs.css'],
          'css/ie.css': ['css/ie.css']
        }
      }
    },

    watch: {
      files: ['src/**.js', 'src/**/*.scss'],
      tasks: the_tasks,
    },
  });


  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');


  grunt.registerTask('default', the_tasks);
  grunt.registerTask('dev', the_tasks_watch);

};