angular.module('app.services')
    .service('Project',['$resource','$filter','$httpParamSerializer','appConfig',
        function($resource,$filter,$httpParamSerializer,appConfig){
            function transformData(data){
                //transforma todos retorno que passam por post do due_date para formato americano do DB
                if(angular.isObject(data) && data.hasOwnProperty('due_date')){
                    //copia o objeto data
                    var o = angular.copy(data);
                    //feito isso para evitar de alterar o dado que vem do modelo
                    o.due_date = $filter('date')(data.due_date,'yyyy-MM-dd');
                    //usa uma lib do angular para serializar o dado
                    return appConfig.utils.transformRequest(o);
                }
                return data;
            }
            return $resource(appConfig.baseUrl + '/project/:id',{id:'@id'},{
                save: {
                    method: 'POST',
                    transformRequest:transformData
                },
                get: {
                    method: 'GET',
                    transformResponse: function (data, headers) {
                        var o = appConfig.utils.transformResponse(data,headers);
                        //transforma todos retorno que passam por post do due_date para formato americano do DB
                        if(angular.isObject(o) && o.hasOwnProperty('due_date')){
                            var arrayDate = o.due_date.split('-');
                            var month = parseInt(arrayDate[1])-1
                            o.due_date = new Date(arrayDate[0],month,arrayDate[2]);
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