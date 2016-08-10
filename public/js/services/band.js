materialAdmin
    .service('bandService', ['$http', function($http){
        this.getBand = function() {
            return $http({
                method: 'get',
                url: "/band"
              });
        };
        
        this.edit = function(sent){
            return $http({
                method: 'post',
                url: '/band/edit',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param(sent)
            });
        };
        
        this.getMember = function(email){
            return $http({
                method: 'get',
                url: '/band/members/' + email
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
        
        this.editMember = function(sent){
            return $http({
                method: 'post',
                url: '/band/members/edit',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param(sent)
            });
        };
    }]);