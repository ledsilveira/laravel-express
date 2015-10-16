angular.module('app.controllers')
    .controller('ProjectTaskRemoveController',[
        '$scope','$location','$routeParams','ProjectTask',function($scope,$location,$routeParams,ProjectTask){

            $scope.task = ProjectTask.get({
                project_id:$routeParams.project_id,
                idTask:$routeParams.idTask});

            $scope.remove = function() {
                $scope.task.$delete({
                    idTask:$routeParams.idTask}).then(function(){
                    $location.path('/project/'+$routeParams.project_id+'/tasks');
                })
            }

        }]);