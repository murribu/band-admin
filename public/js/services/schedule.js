materialAdmin
    .service('scheduleService', ['$http', function($http){
        this.getEvents = function(){
            return $http({
                method: 'get',
                url: '/events'
            });
        };
    }]);