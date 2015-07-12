module.exports = function (grunt) {
    'use strict';
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        copy: {
            main: {
                files: [
                    {
                        flatten: true,
//                        cwd: './bower_components/font-awesome/fonts/',
                        src: ['*.*'],
                        dest: './fonts/',
                        expand: true
                    }
                ]
            }
        },
        concat: {
            options: {
                separator: ';'
            },
            lib: {
                src: [
                    './bower_components/jquery/dist/jquery.js',
                    './bower_components/jquery-ui/jquery-ui.js',
                    //'./public/js/jquery.qtip-1.0.0-rc3.min.js',
                    './public/js/tagit/tag-it.min.js',
                    './public/js/autosize/jquery.autosize.js',
                    './public/js/jquery.hotkeys.js',
//                    './bower_components/modernizr/modernizr.js',
                ],
                dest: './public/js/libs.js'
            },
            main: {
                src: [
                    './public/js/general.js',
                ],
                dest: './public/js/main.js'
            }
        },
        uglify: {
            options: {
                // the banner is inserted at the top of the output
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
            },
            dist: {
                files: {
                    'public/js/libs.min.js': ['<%= concat.lib.dest %>'],
                    'public/js/main.min.js': ['<%= concat.main.dest %>']
                }
            }
        },
        watch: {
            js : {
                files: [
                    './public/js/*.js'
                ],
                tasks: ['concat:main']
            },
            config: {
                files: [
                    'Gruntfile.js',
                    //'bower.json',
                    'package.json'
                ],
                tasks: ['concat']
            }
        },
        concat_css: {
            options: {
              // Task-specific options go here. 
            },
            all: {
                src: [
                    './public/css/bootstrap/bootstrap-theme.min.css',
                    './public/css/bootstrap/bootstrap.min.css',
                    './public/css/jquery-ui-1.8.9.custom.css',
                    './public/css/tagit/jquery.tagit.css',
                    './public/css/tagit/tagit.ui-zendesk.css',
                    './public/css/boxy.css',
                    './public/css/estilo.css',
                    './public/css/estilo_celular.css',
                ],
                dest: './public/css/style.css'
            },
        },
    });
    // Plugin loading
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-concat-css');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');

    // Task definition
    grunt.registerTask('build', ['uglify', 'concat', 'concat_css', 'copy']);
    grunt.registerTask('default', ['build', 'watch']);
};
