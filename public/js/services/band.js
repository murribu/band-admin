materialAdmin
    .service('bandService', ['$http', function($http){
        this.getBand = function() {
            return $http({
                method: 'get',
                url: "/band"
              });
        };
        
        this.addMember = function(sent){
            return $http({
                method: 'post',
                url: '/band/members/add',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param(sent)
            });
        };
    }]);