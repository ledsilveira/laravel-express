angular.module('app.services')
    .service('ProjectTask',['$resource','$filter','$httpParamSerializer','appConfig',
        function($resource,$filter,$httpParamSerializer,appConfig){
            function transformData(data){
                var o = angular.copy(data);
                //transforma todos retorno que passam por post do due_date para formato americano do DB
                if(angular.isObject(data)){

                    if( data.hasOwnProperty('due_date')) {
                        //feito isso para evitar de alterar o dado que vem do modelo
                        o.due_date = $filter('date')(data.due_date, 'yyyy-MM-dd');
                    }
                    if( data.hasOwnProperty('start_date')) {
                        o.start_date = $filter('date')(data.start_date, 'yyyy-MM-dd');
                    }
                    //usa uma lib do angular para serializar o dado
                    return appConfig.utils.transformRequest(o);
                }
                return data;
            }
            return $resource(appConfig.baseUrl + '/project/:project_id/task/:idTask',{project_id:'@project_id',idTask:'@idTask'},{
                save: {
                    method: 'POST',
                    transformRequest:transformData
                },
                get: {
                    method: 'GET',
                    transformResponse: function (data, headers) {
                        var o = appConfig.utils.transformResponse(data,headers);
                        //transforma todos retorno que passam por post do due_date para formato americano do DB
                        if(angular.isObject(o)) {

                            if (o.hasOwnProperty('due_date') && o.due_date) {
                                if(o.due_date === '0000-00-00' ) {
                                    o.due_date = "";
                                } else {
                                    var arrayDate = o.due_date.split('-');
                                    var month = parseInt(arrayDate[1]) - 1
                                    o.due_date = new Date(arrayDate[0], month, arrayDate[2]);
                                }
                            }

                            if (o.hasOwnProperty('start_date') && o.start_date) {
                                if(o.start_date === '0000-00-00' ){
                                    o.start_date = "";
                                } else {
                                    var arrayDate = o.start_date.split('-');
                                    var month = parseInt(arrayDate[1]) - 1
                                    o.start_date = new Date(arrayDate[0], month, arrayDate[2]);
                                }
                            }
                        }
                        return o;
                    }
                },
                update: {
                    method: 'PUT',
                    transformRequest:transformData
                }
            });
    }])