angular.module('app.services')
    .service('ProjectMember',['$resource','appConfig',function($resource,appConfig){
        return $resource(appConfig.baseUrl + '/project/:project_id/member/:idMember',{project_id:'@project_id', idMember:'@idMember'},{
            update: {
                method: 'PUT'
            }

        });
    }]);