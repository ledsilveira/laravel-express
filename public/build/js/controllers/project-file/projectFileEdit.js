angular.module('app.controllers')
    .controller('ProjectFileEditController',[
        '$scope','$location','$routeParams','ProjectFile',function($scope,$location,$routeParams,ProjectFile){

            $scope.file = ProjectFile.get({
                project_id:null,
                idFile:$routeParams.idfile});
            $scope.edit = function() {
                if($scope.form.$valid)
                {
                    ProjectFile.update({project_id:null,idFile:$scope.file.id},$scope.file, function(){
                        $location.path('/project/'+$scope.file.project_id+'/files');
                    })
                }
            }

        }]);