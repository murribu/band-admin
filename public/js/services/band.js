materialAdmin
    .service('bandService', ['$http', function($http){
        this.getBand = function() {
            return $http({
                method: 'get',
                url: "/band"
              });
        };
    }]);