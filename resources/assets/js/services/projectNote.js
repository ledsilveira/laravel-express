angular.module('app.services')
    .service('ProjectNote',['$resource','appConfig',function($resource,appConfig){
        return $resource(appConfig.baseUrl + '/project/:project_id/note/:idnote',{project_id:'@project_id',idnote:'@idnote'},{
            update: {
                method: 'PUT'
            }
        });
    }])