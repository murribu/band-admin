materialAdmin
    .controller('addEventCtrl', function($timeout, $scope, $state, growlService, bandService){
        var self = this;
        self.edit = false;
        self.e = {};
        
        if ($state.params && $state.params.slug){
            bandService.getEvent($state.params.slug).success(function(d){
                if (d['slug']){
                    self.e = d;
                    // $("#event-time").data('DateTimePicker').date(new Date(new Date(d.start_time_local)));
                    // $("#event-date").data('DateTimePicker').date(new Date(new Date(d.start_time_local)));
                    var dt = new Date(new Date(d.start_time_local));
                    $("#event-time").datetimepicker({
                        format: 'h:mm A',
                        useCurrent: false,
                        defaultDate: dt
                    });
                    $("#event-date").datetimepicker({
                        format: 'MMMM D, YYYY',
                        useCurrent: false,
                        defaultDate: dt
                    });
                    self.e.t = moment(dt).format('h:mm A');
                    self.e.date = moment(dt).format('MMMM D, YYYY');
                    self.edit = true;
                }else{
                    $state.go("band.schedule");
                }
            });
        }
        
        self.open = function($event, opened) {
            $event.preventDefault();
            $event.stopPropagation();

            $scope[opened] = true;
        };
        
        self.editEvent = function(){
            var sent = {
                start_time_local: moment(self.e.date).format('YYYY-MM-DD') + ' ' + moment(self.e.t).format('H:mm:00'),
                venue: self.e.venue,
                address1: self.e.address1,
                address2: self.e.address2,
                city: self.e.city,
                state: self.e.state,
                zip: self.e.zip,
                contact: self.e.contact,
                description: self.e.description,
                timezone: self.e.timezone,
                active: self.e.active,
            };
            if (!self.edit){
                bandService.addEvent(sent).success(function(d){
                    growlService.growl('Saved!', 'success');
                    $state.go('band.schedule');
                });
            }else{
                sent['slug'] = self.e.slug;
                bandService.editEvent(sent).success(function(d){
                    growlService.growl('Saved!', 'success');
                    $state.go('band.schedule');
                });
            }
        };
        
        $("#event-time").datetimepicker({
            format: 'h:mm A',
            useCurrent: false,
            defaultDate: new Date(new Date().setHours(19, 0, 0))
        });
        $("#event-date").datetimepicker({
            format: 'MMMM D, YYYY',
        });
    });