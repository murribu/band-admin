materialAdmin
    .controller('addBandMemberCtrl', function($timeout, $scope, $state, growlService, bandService){
        var self = this;
        self.email = '';
        self.name = '';
        self.edit = false;
        
        if ($state.params && $state.params.email){
            bandService.getMember($state.params.email).success(function(d){
                if (d['email']){
                    self.email = d['email'];
                    self.name = d['name'];
                    self.edit = true;
                }else{
                    $state.go("band.details");
                }
            });
        }
        self.editMember = function(){
            if (self.email != ''){
                $("[name='email']").removeClass('has-error');
                if (self.edit){
                    var sent = {
                        oldemail: $state.params.email,
                        newemail: self.email,
                        name: self.name,
                    }
                    bandService.editMember(sent).success(function(d){
                        growlService.growl('Saved!', 'success');
                        $state.go('band.editmember', {email: self.email });
                    }).error(function(d){
                        growlService.growl('Your changes were not saved. There was a problem: ' + d.message, 'danger');
                    });
                }else{
                    var sent = {
                        email: self.email,
                    }
                    bandService.addMember(sent).success(function(d){
                        growlService.growl('Saved!', 'success');
                        $state.go('band.details');
                    }).error(function(d){
                        growlService.growl('Your changes were not saved. There was a problem: ' + d.message, 'danger');
                    });
                }
            }else{
                $(".form-group[data-group='email']").addClass('has-error');
            }
        }
        bandService.getBand().success(function(d){
            self.band = d;
        });
    });