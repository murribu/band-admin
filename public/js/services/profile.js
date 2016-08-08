materialAdmin
    .service('profileService', ['$http', function($http){
        this.getProfile = function() {
            return $http({
                method: 'get',
                url: "/auth/me"
              });
        };
        
        this.logout = function() {
            return $http({
                method: 'get',
                url: '/logout'
            });
        };
    }]);