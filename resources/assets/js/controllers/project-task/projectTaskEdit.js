angular.module('app.controllers')
    .controller('ProjectTaskEditController',[
        '$scope','$location','$routeParams','ProjectTask','appConfig',function($scope,$location,$routeParams,ProjectTask,appConfig){

            $scope.task = ProjectTask.get({
                project_id:$routeParams.project_id,
                idTask:$routeParams.idTask
            });

            $scope.status = appConfig.projectTask.status;

            $scope.start_date = {
                status: {
                    opened: false
                }
            };
            $scope.due_date = {
                status: {
                    opened: false
                }
            };

            $scope.openStartDatePicker = function($event) {
                $scope.start_date.status.opened = true;
            };

            $scope.openDueDatePicker = function($event) {
                $scope.due_date.status.opened = true;
            };

            //poderia fazer assim pra usar referência indireta
            $scope.task.project_id = $routeParams.project_id;
            $scope.save = function() {
                if($scope.form.$valid)
                {
                    ProjectTask.update({
                        project_id:$routeParams.project_id,
                        idTask:$scope.task.id},
                        $scope.task,function(){
                            $location.path('/project/'+$routeParams.project_id+'/tasks');
                    });
                }
            }

        }]);