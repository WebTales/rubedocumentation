angular.module("rubedoBlocks").lazy.controller("DocApiController",["$scope",'DocApiService', function($scope, DocApiService){
    var me = this;
    DocApiService.getDocs().then(function(response){
        if(response.data.success){
            console.log(response.data.docs);
            me.routes = response.data.docs;
        }
    });
}]);
angular.module("rubedoBlocks").lazy.factory("DocApiService",['$http', function($http){
    var serviceInstance={};
    serviceInstance.getDocs = function(){
        return ($http.get("/api/v1/docs"));
    };
    return serviceInstance;
}]);