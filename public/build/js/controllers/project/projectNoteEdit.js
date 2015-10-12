angular.module('app.controllers')
    .controller('ProjectNoteEditController',[
        '$scope','$location','$routeParams','ProjectNote',function($scope,$location,$routeParams,ProjectNote){

            $scope.note = ProjectNote.get({project_id:$routeParams.project_id,idnote:$routeParams.idnote});
            $scope.edit = function() {
                if($scope.form.$valid)
                {
                    ProjectNote.update({project_id:$scope.note.project_id,idnote:$scope.note.id},$scope.note, function(){
                        $location.path('/project/'+$scope.note.project_id+'/notes');
                    })
                }
            }

        }]);