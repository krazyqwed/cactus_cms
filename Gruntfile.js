module.exports = function(grunt) {
  grunt.initConfig({
    // Read in the package.json file
    pkg: grunt.file.readJSON('package.json'),

    // Sass task definition
    sass: {
      dist: {
        options: {
          style: 'compressed',
          noCache: false
        },
        files: [{
          expand: true,
          cwd: 'res/css/admin/sass/',
          src: '**/*.{scss,sass}',
          dest: 'res/css/admin/',
          ext: '.css'
        }]
      }
    },

    // Watch task definiton
    watch: {
      sass: {
        files: ['scss/**/*.{scss,sass}'],
        tasks: ['sass']
      }
    }
  });

  // Load the plugins
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task(s).
  grunt.registerTask('default', [ 'sass' ]);
};