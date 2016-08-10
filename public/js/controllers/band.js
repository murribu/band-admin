materialAdmin
    .controller('bandCtrl', function($timeout, $scope, $state, growlService, bandService){
        var self = this;
        self.band = {};
        
        bandService.getBand().success(function(d){
            self.band = d;
        });
        
        self.editband = function(){
            var sent = {
                name: self.band.name,
            };
            bandService.edit(sent).success(function(d){
                self.band = d;
                growlService.growl('Saved!', 'success');
            }).error(function(d){
                growlService.growl('Your changes were not saved. There was a problem.', 'danger');
            });
        };
    });