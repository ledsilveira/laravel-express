angular.module('app.controllers')
.controller('LoginController',['$scope','$location','OAuth',function($scope,$location,OAuth){
        $scope.user ={
            username:'',
            password:''
        };
        $scope.error ={
            message:'',
            error:false
        };

        $scope.login = function(){
            //verifica se os dados do formulario sao validos, se sim faz login
            if( $scope.formlogin.$valid )
            {
                OAuth.getAccessToken($scope.user).then(function(){
                    $location.path('home');
                },function(data){
                    $scope.error.error = true;
                    $scope.error.message = data.data.error_description;
                });
            }
        };
    }]);