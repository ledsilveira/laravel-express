angular.module('app.controllers')
    .controller('ProjectNoteNewController',[
        '$scope','$location','$routeParams','ProjectNote',function($scope,$location,$routeParams,ProjectNote){
            $scope.note = new ProjectNote({project_id:$routeParams.project_id});
            $scope.save = function() {
                if($scope.form.$valid)
                {
                    $scope.note.$save().then(function(){
                        $location.path('/project/'+$routeParams.project_id+'/notes');
                    });
                }
            }

        }]);