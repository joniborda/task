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
                    './public/js/jquery.js',
                    './public/js/jquery.ui-1-10.js',
                    './public/js/jquery.boxy.js',
                    './public/js/jquery.qtip-1.0.0-rc3.min.js',
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
        }
    });
    // Plugin loading
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');
    // Task definition
    grunt.registerTask('build', ['uglify', 'concat', 'copy']);
    grunt.registerTask('default', ['build', 'watch']);
};
