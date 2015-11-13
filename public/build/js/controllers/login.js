angular.module('app.controllers')
.controller('LoginController',['$scope','$location','$cookies','User','OAuth',
        function($scope,$location,$cookies,User,OAuth){
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
                    //primeiro sem parametros, segundo sem dados e terceiro a funcao de sucesso
                    User.autenthicated({},{},function(data){
                        $cookies.putObject('user',data);
                        $location.path('painel');
                    });

                },function(data){
                    $scope.error.error = true;
                    $scope.error.message = data.data.error_description;
                });
            }
        };
    }]);