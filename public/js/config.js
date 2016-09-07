materialAdmin
    .config(function ($stateProvider, $urlRouterProvider){
        $urlRouterProvider.otherwise("/home");


        $stateProvider
        
            //------------------------------
            // HOME
            //------------------------------

            .state ('home', {
                url: '/home',
                templateUrl: 'views/home.html'
            })


            //------------------------------
            // HEADERS
            //------------------------------
            .state ('headers', {
                url: '/headers',
                templateUrl: 'views/common-2.html'
            })

            .state('headers.textual-menu', {
                url: '/textual-menu',
                templateUrl: 'views/textual-menu.html'
            })

            .state('headers.image-logo', {
                url: '/image-logo',
                templateUrl: 'views/image-logo.html'
            })

            .state('headers.mainmenu-on-top', {
                url: '/mainmenu-on-top',
                templateUrl: 'views/mainmenu-on-top.html'
            })

            //------------------------------
            // BAND DETAILS
            //------------------------------

            
            .state ('band', {
                url: '/band',
                templateUrl: 'views/common.html'
            })
            
            .state('band.details', {
                url: '/details',
                templateUrl: 'views/band-details.html'
            })
            
            .state('band.addmember', {
                url: '/members/add',
                templateUrl: 'views/band-add-member.html'
            })
            
            .state('band.editmember', {
                url: '/members/:email',
                templateUrl: 'views/band-add-member.html'
            })
            
            .state('band.schedule', {
                url: '/schedule',
                templateUrl: 'views/band-schedule.html'
            })
            
            .state('band.addevent', {
                url: '/schedule/add',
                templateUrl: 'views/band-add-event.html',
                resolve: {
                    loadPlugin: function($ocLazyLoad) {
                        return $ocLazyLoad.load ([
                            {
                                name: 'css',
                                insertBefore: '#app-level',
                                files: [
                                    'vendors/bower_components/nouislider/jquery.nouislider.css',
                                    'vendors/farbtastic/farbtastic.css',
                                    'vendors/bower_components/summernote/dist/summernote.css',
                                    'vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
                                    'vendors/bower_components/chosen/chosen.min.css'
                                ]
                            },
                            {
                                name: 'vendors',
                                files: [
                                    'vendors/input-mask/input-mask.min.js',
                                    'vendors/bower_components/nouislider/jquery.nouislider.min.js',
                                    'vendors/bower_components/moment/min/moment.min.js',
                                    'vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
                                    'vendors/bower_components/summernote/dist/summernote.min.js',
                                    'vendors/fileinput/fileinput.min.js',
                                    'vendors/bower_components/chosen/chosen.jquery.js',
                                    'vendors/bower_components/angular-chosen-localytics/chosen.js',
                                ]
                            }
                        ])
                    }
                }
            })
            
            .state('band.editevent', {
                url: '/schedule/:slug',
                templateUrl: 'views/band-add-event.html',
                resolve: {
                    loadPlugin: function($ocLazyLoad) {
                        return $ocLazyLoad.load ([
                            {
                                name: 'css',
                                insertBefore: '#app-level',
                                files: [
                                    'vendors/bower_components/nouislider/jquery.nouislider.css',
                                    'vendors/farbtastic/farbtastic.css',
                                    'vendors/bower_components/summernote/dist/summernote.css',
                                    'vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
                                    'vendors/bower_components/chosen/chosen.min.css'
                                ]
                            },
                            {
                                name: 'vendors',
                                files: [
                                    'vendors/input-mask/input-mask.min.js',
                                    'vendors/bower_components/nouislider/jquery.nouislider.min.js',
                                    'vendors/bower_components/moment/min/moment.min.js',
                                    'vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
                                    'vendors/bower_components/summernote/dist/summernote.min.js',
                                    'vendors/fileinput/fileinput.min.js',
                                    'vendors/bower_components/chosen/chosen.jquery.js',
                                    'vendors/bower_components/angular-chosen-localytics/chosen.js',
                                ]
                            }
                        ])
                    }
                }
            })
            
            //------------------------------
            // CALENDAR
            //------------------------------
            
            .state ('calendar', {
                url: '/calendar',
                templateUrl: 'views/calendar.html',
                resolve: {
                    loadPlugin: function($ocLazyLoad) {
                        return $ocLazyLoad.load ([
                            {
                                name: 'css',
                                insertBefore: '#app-level',
                                files: [
                                    'vendors/bower_components/fullcalendar/dist/fullcalendar.min.css',
                                ]
                            },
                            {
                                name: 'vendors',
                                files: [
                                    'vendors/bower_components/moment/min/moment.min.js',
                                    'vendors/bower_components/fullcalendar/dist/fullcalendar.min.js'
                                ]
                            }
                        ])
                    }
                }
            })
        
            //------------------------------
            // BREADCRUMB DEMO
            //------------------------------
            .state ('breadcrumb-demo', {
                url: '/breadcrumb-demo',
                templateUrl: 'views/breadcrumb-demo.html'
            })
    });
