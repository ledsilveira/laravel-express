angular.module('app.controllers')
    .controller('ProjectFileListController',[
        '$scope','$location','$routeParams','ProjectFile',function($scope,$location,$routeParams,ProjectFile){

            $scope.projectFiles = ProjectFile.query({project_id:$routeParams.project_id});

        }]);