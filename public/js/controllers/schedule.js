materialAdmin
    .controller('scheduleCtrl', function($timeout, $scope, $state, growlService, bandService){
        var self = this;
        
        self.events = [];
        
        bandService.getEvents().success(function(d){
            self.events = d;
        });
        
        self.displayDateTime = function(dt){
            return moment(dt).format('MMMM d, YYYY h:mm A');
        }
    });