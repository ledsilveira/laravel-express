angular.module('app.controllers')
    .controller('ProjectNewController',[
        //carrega o appConfig para pegar a lista de status
        //$cookies para pegar o owner id
        '$scope','$location','$cookies','Project','Client','appConfig',
        function($scope, $location,$cookies, Project,Client, appConfig){
            $scope.project = new Project();
            $scope.status = appConfig.project.status;

            // scope.due_date e a funcao open tratam de controlar a abertura do datepicker
            $scope.due_date = {
              status:{
                  opened: false
              }
            };
            $scope.open = function($event){
                $scope.due_date.status.opened = true;
            };

            $scope.save = function() {
                if($scope.form.$valid)
                {
                    //pega o id do user que está setado no cookie e seta como dono do projeto
                    $scope.project.owner_id = $cookies.getObject('user').id;
                    $scope.project.$save().then(function(){
                        $location.path('/projects');
                    });
                }
            };

            //formata o typeahead do autocomple de clientes para mostrar o nome
            $scope.formatName = function(model) {
                //verifica se veio valor valido nao null
                if(model){
                    return model.name;
                }
                return '';
            };

            //requisicao ajax para carregar autocomple clientes
            $scope.getClient = function(name){
                //$promise; $promise faz que a requisicao espere os dados serem recebidos para retornar
                return Client.query({
                    search: name,
                    searchFields:'name:like'
                }).$promise;
            };

            $scope.selectClient = function (item){
                $scope.project.client_id = item.id;
            };

        }]);