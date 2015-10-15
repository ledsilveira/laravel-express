angular.module('app.controllers')
    .controller('ProjectNewController',[
        //carrega o appConfig para pegar a lista de status
        //$cookies para pegar o owner id
        '$scope','$location','$cookies','Project','Client','appConfig',
        function($scope, $location,$cookies, Project,Client, appConfig){
            $scope.project = new Project();
            $scope.clients = Client.query();
            $scope.status = appConfig.project.status;

            $scope.save = function() {
                if($scope.form.$valid)
                {
                    //pega o id do user que está setado no cookie e seta como dono do projeto
                    $scope.project.owner_id = $cookies.getObject('user').id;
                    $scope.project.$save().then(function(){
                        $location.path('/projects');
                    });
                }
            }

        }]);