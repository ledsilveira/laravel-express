angular.module('app.controllers')
    .controller('ProjectNoteCrudController',[
        '$scope','$location','$routeParams','ProjectNote',function($scope,$location,$routeParams,ProjectNote){

            $scope.notes = ProjectNote.query({project_id:$routeParams.project_id});

        }]);