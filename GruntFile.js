module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        tinypng: {
            options: {
                apiKey: '38_9e5XLkopmjFIH3wqIyenDt6SKJ_xM',
                summarize: true,
                showProgress: true,
                stopOnImageError: true
            },
            compresspngimage: {
                expand: true,
                src: '_img/*.png',
                dest: '' // After the image is compress it returns back to the folder then the copy task will take it to the correct build folder.
            }
        },
        assemble: {
            options: {
                layoutdir: '_html/template/',
                layout: "main.hbs",
                flatten: true,
                partials: '_html/components/**/*.hbs',
                ext: '.php'
            },
            site: {
              files: {
                  'sparta_v0.0.1/': ['_html/pages/*.hbs']
              }
            }
        },
        sass: {
            dist: {
                options: {
                    style: 'compressed',
                    sourcemap: 'none'
                },
                files: {
                    'sparta_v0.0.1/assets/css/style.css': '_scss/style.scss'
                }
            }
        },
        concat: {
            options: {
                sourceMap: false,
                stripBanners: {
                    block: true
                },

            },
            js: {
                src: ['bower_components/jquery/dist/jquery.min.js','bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js','bower_components/waypoints/lib/jquery.waypoints.min.js','bower_components/smooth-scroll/dist/js/smooth-scroll.min.js','bower_components/bootstrap-validator/dist/validator.min.js','bower_components/masonry/dist/masonry.pkgd.min.js','_js/*.js'],
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
            php: {
                files: [{
                    expand: true,
                    cwd: '_php',
                    src: ['**/*.{php,ini,html,txt}'],
                    dest: 'sparta_v0.0.1/assets/inc/'
                }]
            }
        },
        clean: {
            all: ['sparta_v0.0.1/assets/inc/*','sparta_v0.0.1/*.html','sparta_v0.0.1/assets/js/*.js','sparta_v0.0.1/assets/img/*']
        },
        watch: {
            sass: {
                files: '_scss/*.scss',
                tasks: ['sass:dist']
            },
            js: {
                files:  ['bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js', '_js/*.js'],
                tasks: ['concat:js','uglify:minify_js']
            },
            copy: {
                files: ['_img/*','_php/*'],
                tasks: ['copy:images','copy:php']
            },
            assemble: {
                files: ['_html/layouts/main.hbs', '_html/pages/*.hbs','_html/components/**/*.hbs'],
                tasks: ['assemble']
            }

        }, 
        compress: {
            main: {
                options: {
                    archive: 'mzPortfolio.zip',
                },
                files: [{ 
                    expand: true,
                    src: ['sparta_v0.0.1/**/*']
                }] 
            }
        },
        uglify: {
            minify_js: {
                files: {
                    'sparta_v0.0.1/assets/js/scripts.min.js' : ['sparta_v0.0.1/assets/js/scripts.min.js']
                }
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('assemble');
    grunt.loadNpmTasks('grunt-tinypng');
    grunt.registerTask('default', ['clean','assemble','concat','sass','copy','uglify','watch']);
    grunt.registerTask('tinypngfire',['tinypng']);
    grunt.registerTask('compressProdFiles',['compress:main']);
}; 