var sassFiles = {
    'sparta_v0.0.1/assets/css/style.css': '_scss/style.scss'
};

module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        assemble: {
            options: {
                layout: "_html/layouts/main.hbs",
                flatten: true,
                partials: '_html/components/*.hbs'
            },
            site: {
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
                src: ['bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.min.js','bower_components/waypoints/lib/noframework.waypoints.min.js','_js/*'],
                dest:'sparta_v0.0.1/assets/js/scripts.min.js'
            }
        },
        copy: {
            images: {
                files: [{
                    expand: true,
                    cwd: '_img/',
                    src: ['**/*.{png,jpg,svg,PNG,JPG}'],
                    dest: 'sparta_v0.0.1/assets/img/'
                }]
            },
            videos: {
                files: [{
                    expand: true,
                    cwd: '_video/',
                    src: ['**/*.{mp4,ogv,webm}'],
                    dest: 'sparta_v0.0.1/assets/videos/'
                }]
            }
        },
        clean: {
            all: ['sparta_v0.0.1/*.html','sparta_v0.0.1/assets/js/*.js','sparta_v0.0.1/assets/img/*']
        },
        watch: {
            sass: {
                files: '_scss/*.scss',
                tasks: ['sass:dist']
            },
            js: {
                files:  ['bower_components/bootstrap-sass-official/assets/javascripts/bootstrap.min.js', '_js/*'],
                tasks: ['concat:js']
            },
            copy: {
                files: ['_video/*','_img/*'],
                tasks: ['copy:videos', 'copy:images']
            },
            assemble: {
                files: ['_html/layouts/main.hbs', '_html/pages/*.hbs','_html/components/*.hbs'],
                tasks: ['assemble']
            }

        }
       

    });
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('assemble');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.registerTask('default', ['clean','assemble','concat','buildcss','copy']);
    grunt.registerTask('buildcss', ['sass:dist']);
}; 