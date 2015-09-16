var sassFiles = {
    'sparta_v0.0.1/assets/css/style.css': '_scss/style.scss'
};

module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        assemble: {
            options: {
                layout: "_html/layouts/default.hbs",
                flatten: true
            },
            pages: {
              files: {
                  'sparta_v0.0.1/': ['_html/pages/*.hbs']
              }
            }
        },
        sass: {
            dev: {
                options: {
                    style: 'expanded'
                },
                files: sassFiles
            },
            dist: {
                options: {
                    style: 'expanded',
                    sourcemap: 'none'
                },
                files: sassFiles
            }
        },
        concat: {
            options: {
                sourceMap: false,
            },
            js: {
                src: ['bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.min.js', 'assets/js/master.js'],
                dest:'sparta_v0.0.1/assets/js/all_scripts.min.js'
            }
        },
        watch: {
            sass: {
                files: '_scss/*.scss',
                tasks: ['sass:dist']
            },
            js: {
                files:  ['bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.min.js', 'sparta_v0.0.1/assets/js/master.js'],
                tasks: ['concat:js']
            }
        },
        clean: {
            all: ['sparta_v0.0.1/*.html']
        }

    });
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('assemble');
    grunt.registerTask('default', ['clean', 'assemble','concat','buildcss']);
    grunt.registerTask('buildcss', ['sass:dist']);
};