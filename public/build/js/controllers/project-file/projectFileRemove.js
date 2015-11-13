angular.module('app.controllers')
    .controller('ProjectFileRemoveController',[
        '$scope','$location','$routeParams','ProjectFile',function($scope,$location,$routeParams,ProjectFile){

            $scope.file = ProjectFile.get({
                project_id:null,
                idFile:$routeParams.idFile});

            $scope.remove = function() {
                $scope.file.$delete({project_id:null,idFile:$routeParams.idFile}).then(function(){
                    $location.path('/project/'+$routeParams.project_id+'/files');
                })
            }

        }]);