angular.module('app.controllers')
    .controller('ProjectEditController',[
        //carrega o appConfig para pegar a lista de status
        //$cookies para pegar o owner id
        '$scope','$routeParams','$location','$cookies','Project','Client','appConfig',
        function($scope, $routeParams, $location,$cookies, Project,Client, appConfig){
            $scope.project = Project.get({id:$routeParams.id});
            $scope.clients = Client.query();
            $scope.status = appConfig.project.status;

            $scope.update = function() {
                if($scope.form.$valid)
                {
                    //pega o id do user que está setado no cookie e seta como dono do projeto
                    $scope.project.owner_id = $cookies.getObject('user').id;
                    Project.update({id:$scope.project.project_id},$scope.project,function(){
                        $location.path('/projects');
                    });

                }
            }

        }]);