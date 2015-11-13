angular.module('app.controllers')
    .controller('ProjectMemberRemoveController',[
        '$scope','$location','$routeParams','ProjectMember',
        function($scope,$location,$routeParams,ProjectMember){

            $scope.member = ProjectMember.get({
                project_id:$routeParams.project_id,
                idMember:$routeParams.idMember});

            $scope.remove = function() {
                $scope.member.$delete({
                    project_id:$routeParams.project_id,
                    idMember:$routeParams.idMember
                }).then(function(){
                    $location.path('/project/'+$routeParams.project_id+'/members');
                })
            }

        }]);