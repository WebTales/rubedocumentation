angular.module("rubedoBlocks").lazy.controller("DocApiController",["$scope",'DocApiService', function($scope, DocApiService){
    var me = this;
    DocApiService.getDocs().then(function(response){
        if(response.data.success){
            me.routes =  {};
            angular.forEach(response.data.docs, function(doc){
                var route = {};
                var urlSegment = doc.url.split('/');
                for (i = 0; i < 3; i++){
                    urlSegment.shift();
                }
                if(!me.routes[urlSegment[0]]){
                    me.routes[urlSegment[0]] = {};
                    me.routes[urlSegment[0]].url = '/'+urlSegment[0];
                    me.routes[urlSegment[0]].routes = [];
                }
                route.url = '/'+urlSegment.join('/');
                route.details = doc.options;
                angular.forEach(route.details.verbs, function(verb){
                    route.details.verbs[verb.verb].showDetails = false;
                    route.details.verbs[verb.verb].showExamples = false;
                });
                me.routes[urlSegment[0]].routes.push(route);
                if(doc.optionsEntity){
                    var routeEntity = {};
                    routeEntity.url = route.url+'/{id}';
                    routeEntity.details = doc.optionsEntity;
                    angular.forEach(routeEntity.details.verbs, function(verb){
                        routeEntity.details.verbs[verb.verb].showDetails = false;
                    });
                    me.routes[urlSegment[0]].routes.push(routeEntity);
                }
            });
            console.log(me.routes);
        }
    });
    me.showDetails = function (doc, route, verb){
        me.routes[doc].routes[route].details.verbs[verb].showDetails = !me.routes[doc].routes[route].details.verbs[verb].showDetails;
        me.routes[doc].routes[route].details.verbs[verb].showExamples = false;
    };
    me.showExamples = function (doc, route, verb, activate){
        me.routes[doc].routes[route].details.verbs[verb].showExamples = activate;
    };
}]);
angular.module("rubedoBlocks").lazy.factory("DocApiService",['$http', function($http){
    var serviceInstance={};
    serviceInstance.getDocs = function(){
        return ($http.get("/api/v1/docs"));
    };
    return serviceInstance;
}]);