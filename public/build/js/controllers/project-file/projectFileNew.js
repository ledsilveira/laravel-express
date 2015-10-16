angular.module('app.controllers')
    .controller('ProjectFileNewController',[
        '$scope','$location','$routeParams','appConfig','Url','Upload',
        function($scope,$location,$routeParams,appConfig,Url,Upload) {

            $scope.save = function() {
                if($scope.form.$valid)
                {
                    var url = appConfig.baseUrl +
                        Url.getUrlFromUrlSymbol(appConfig.urls.projectFile, {
                            project_id:$routeParams.project_id,
                            ifFile:''
                        });
                    Upload.upload({
                        url: url,
                        fields:{
                            name: $scope.projectFile.name,
                            description: $scope.projectFile.description,
                            project_id: $routeParams.project_id
                        },
                        file: $scope.projectFile.file
                    }).success(function (data,status,headers,config) {
                        $location.path('/project/'+$routeParams.project_id+'/files');
                    });
                }
            }

        }]);