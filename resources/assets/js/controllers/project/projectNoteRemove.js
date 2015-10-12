angular.module('app.controllers')
    .controller('ProjectNoteRemoveController',[
        '$scope','$location','$routeParams','ProjectNote',function($scope,$location,$routeParams,ProjectNote){

            $scope.note = ProjectNote.get({project_id:$routeParams.project_id,idnote:$routeParams.idnote});

            $scope.remove = function() {
                $scope.note.$delete({project_id:$routeParams.project_id,idnote:$routeParams.idnote}).then(function(){
                    $location.path('/project/'+$routeParams.project_id+'/notes');
                })
            }

        }]);