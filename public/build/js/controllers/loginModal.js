angular.module('app.controllers')
    .controller('LoginModalController',
    ['$rootScope','$scope','$location','$cookies','$modalInstance','authService','User','OAuth','OAuthToken',
        function($rootScope,$scope,$location,$cookies,$modalInstance,authService,User,OAuth,OAuthToken){
            $scope.user ={
                username:'',
                password:''
            };
            $scope.error ={
                message:'',
                error:false
            };

            $scope.$on('event:auth-loginConfirmed',function(){
                $rootScope.loginModalOpened = false;
                $modalInstance.close();
            });

            $scope.$on('$routeChangeStart', function(){
                $rootScope.loginModalOpened = false;
                $modalInstance.dismiss('cancel'); //poderia ser o close tbm
            });

            $scope.$on('event:auth-loginCancelled',function(){
                OAuthToken.removeToken();
            });

            $scope.login = function(){
                //verifica se os dados do formulario sao validos, se sim faz login
                if( $scope.formlogin.$valid )
                {
                    OAuth.getAccessToken($scope.user).then(function(){
                        //primeiro sem parametros, segundo sem dados e terceiro a funcao de sucesso
                        User.autenthicated({},{},function(data){
                            $cookies.putObject('user',data);
                            authService.loginConfirmed();
                        });

                    },function(data){
                        $scope.error.error = true;
                        $scope.error.message = data.data.error_description;
                    });
                }
            };

            $scope.cancel = function() {
                authService.loginCancelled();
                $location.path('login');
            };
        }]);