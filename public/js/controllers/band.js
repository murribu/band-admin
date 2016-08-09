materialAdmin
    .controller('bandCtrl', function($timeout, $scope, $state, bandService){
        var self = this;
        bandService.getBand().success(function(d){
            self.band = d;
        });
    });