materialAdmin
    .controller('addBandMemberCtrl', function($timeout, $scope, $state, growlService, bandService){
        var self = this;
        self.email = '';
        self.edit = false;
        
        if ($state.params && $state.params.email){
            self.email = $state.params.email;
            self.edit = true;
        }
        self.addMember = function(){
            if (self.email != ''){
                $("[name='email']").removeClass('has-error');
                let sent = {
                    email: self.email,
                }
                bandService.addMember(sent).success(function(d){
                    growlService.growl('Saved!', 'success');
                    $state.go('band.details');
                });
            }else{
                $(".form-group[data-group='email']").addClass('has-error');
            }
        }
        bandService.getBand().success(function(d){
            self.band = d;
        });
    });