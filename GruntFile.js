var sassFiles = {
    'assets/css/style.css': '_scss/style.scss'
};

module.exports = function(grunt) {
    grunt.initConfig({
        sass: {
            dev: {
                options: {
                    style: 'expanded'
                },
                files: sassFiles
            },
            dist: {
                options: {
                    style: 'compressed',
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
                dest:'assets/js/all_scripts.min.js'
            }
        },
        watch: {
            sass: {
                files: '_scss/*.scss',
                tasks: ['sass:dist']
            },
            js: {
                files:  ['bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.min.js', 'assets/js/master.js'],
                tasks: ['concat:js']
            }
        }

    });
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.registerTask('buildcss', ['sass:dist']);
    grunt.registerTask('default', ["concat","buildcss"]);
};