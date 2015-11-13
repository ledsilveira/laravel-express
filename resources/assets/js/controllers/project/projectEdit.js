angular.module('app.controllers')
    .controller('ProjectEditController',[
        //carrega o appConfig para pegar a lista de status
        //$cookies para pegar o owner id
        '$scope','$routeParams','$location','$cookies','Project','Client','appConfig',
        function($scope, $routeParams, $location,$cookies, Project,Client, appConfig){
            //funcao eh executada quando sucesso da requisicao
            Project.get({id:$routeParams.id}, function(data){
                $scope.project = data;

                $scope.clientSelected = data.client;
                //nao precisa consultar pois jah tras no transformer @todo alterar data.client para data.client.data quando tiver o trasnformer do client
                /*
                Client.get({id:data.client_id},function(data){
                    $scope.clientSelected = data;
                })*/
            });
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

            $scope.update = function() {
                if($scope.form.$valid)
                {
                    //pega o id do user que está setado no cookie e seta como dono do projeto
                    $scope.project.owner_id = $cookies.getObject('user').id;
                    Project.update({project_id:$scope.project.project_id},$scope.project,function(){
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