angular.module('app.controllers')
    .controller('ProjectTaskListController',[
        '$scope','$location','$routeParams','appConfig','ProjectTask',function($scope,$location,$routeParams,appConfig,ProjectTask){
            $scope.projectTask = new ProjectTask();

            $scope.save = function () {
                if($scope.form.$valid){
                    //valor default de status
                    $scope.projectTask.status = appConfig.projectTask.status[0].value;
                    $scope.projectTask.project_id = $routeParams.project_id;
                    $scope.projectTask.$save({project_id: $routeParams.project_id}).then(function(){
                        //zera os dados
                        $scope.projectTask = new ProjectTask();
                        //recarrega as task na tela
                        $scope.loadTask();
                    });
                }
            };
            //$scope.tasks = ProjectTask.query({project_id:$routeParams.project_id});
            $scope.loadTask = function(){
                $scope.projectTasks = ProjectTask.query({
                    project_id:$routeParams.project_id,
                    orderBy:'id',
                    sortedBy:'desc'
                });
            };

            $scope.loadTask();
        }]);