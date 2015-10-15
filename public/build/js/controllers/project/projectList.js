angular.module('app.controllers')
    .controller('ProjectListController',[
        '$scope','$location','$routeParams','Project',function($scope,$location,$routeParams,Project){

            $scope.projects = Project.query();

        }]);