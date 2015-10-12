angular.module('app.services')
    .service('ProjectNote',['$resource','appConfig',function($resource,appConfig){
        return $resource(appConfig.baseUrl + '/project/:project_id/note/:idnote',{id:'@project_id',note:'@idnote'},{
            update: {
                method: 'PUT'
            }
        });
    }])