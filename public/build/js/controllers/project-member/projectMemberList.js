angular.module('app.controllers')
    .controller('ProjectMemberListController',[
        '$scope','$routeParams','ProjectMember','User','$http',
        function($scope,$routeParams,ProjectMember,User,$http){
            $scope.projectMember = new ProjectMember();

            $scope.save = function () {

                //@todo refazer como no projectFile alterando as urls, e usar o scope comentado para manter padrão
                $http({
                    method: 'POST',
                    url: '/project/'+$routeParams.project_id + '/addMember',
                    data: { member_id: $scope.projectMember.member_id }
                }).then(function successCallback(response) {
                    //zera os dados
                    $scope.projectMember = new ProjectMember();
                    //recarrega as member na tela
                    $scope.loadMember();
                }, function errorCallback(response) {
                    //erro
                });

              /*  $scope.project = new Project();
                if($scope.form.$valid){
                    $scope.project.$save({project_id: $routeParams.project_id}).then(function(){
                        //zera os dados
                        $scope.projectMember = new ProjectMember();
                        //recarrega as member na tela
                        $scope.loadMember();
                    });
                }*/
            };
            //$scope.members = ProjectMember.query({project_id:$routeParams.project_id});
            $scope.loadMember = function(){
                $scope.projectMembers = ProjectMember.query({
                    project_id:$routeParams.project_id,
                    orderBy:'id',
                    sortedBy:'desc'
                });
            };

            $scope.formatName = function(model){
                if(model){
                    return model.name;
                }
                return '';
            };

            $scope.getUsers = function(name){
              return User.query({
                  search:name,
                  searchFields: 'name:like'
              }).$promise;
            };

            $scope.selectUser = function(item){
                $scope.projectMember.member_id = item.id;
            };

            $scope.loadMember();

        }]);