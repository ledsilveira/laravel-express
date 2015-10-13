angular.module('app.services')
.service('User',['$resource','appConfig',function($resource,appConfig){
        return $resource(appConfig.baseUrl + '/user/',{},{
            //action personalizada
            autenthicated: {
                url:appConfig.baseUrl + '/user/authenticated',
                method: 'GET'
            }

        });
    }])