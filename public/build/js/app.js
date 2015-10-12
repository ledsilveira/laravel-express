var app = angular.module('app',['ngRoute','angular-oauth2','app.controllers','app.services']);

angular.module('app.controllers',['ngMessages','angular-oauth2']);
angular.module('app.services',['ngResource']);

//provider para setar configurações da app
app.provider('appConfig',function(){
   var config = {
      baseUrl: 'http://localhost:8000'
   };
    return {
        config:config,
        $get: function(){
            return config;
        }
    }
});
/**
 * Dentro do config soh pode receber rotas, aqui serao definidas as rotas
 */
app.config([
    '$routeProvider','OAuthProvider','OAuthTokenProvider','appConfigProvider',
    function($routeProvider,OAuthProvider,OAuthTokenProvider,appConfigProvider){
    $routeProvider
        .when('/login',{
            templateUrl:'build/views/login.html',
            controller:'LoginController'
        })
        .when('/home',{
            templateUrl:'build/views/home.html',
            controller:'HomeController'
        })
        .when('/clients',{
            templateUrl:'build/views/client/list.html',
            controller:'ClientListController'
        })
        .when('/clients/new',{
            templateUrl:'build/views/client/new.html',
            controller:'ClientNewController'
        })
        .when('/clients/:id',{
            templateUrl:'build/views/client/listone.html',
            controller:'ClientEditController'
        })
        .when('/clients/:id/edit',{
            templateUrl:'build/views/client/edit.html',
            controller:'ClientEditController'
        })
        .when('/clients/:id/remove',{
            templateUrl:'build/views/client/remove.html',
            controller:'ClientRemoveController'
        })
        .when('/project/:project_id/notes',{
            templateUrl:'build/views/projectNote/list.html',
            controller:'ProjectNoteCrudController'
        })
        .when('/project/:project_id/notes/:idnote',{
            templateUrl:'build/views/projectNote/listone.html',
            controller:'ProjectNoteEditController'
        })
        .when('/project/:project_id/notes/new',{
            templateUrl:'build/views/projectNote/new.html',
            controller:'ProjectNoteNewController'
        })
        .when('/project/:project_id/notes/:idnote/edit',{
            templateUrl:'build/views/projectNote/edit.html',
            controller:'ProjectNoteEditController'
        })
        .when('/project/:project_id/notes/:idnote/remove',{
            templateUrl:'build/views/projectNote/remove.html',
            controller:'ProjectNoteRemoveController'
        });
    OAuthProvider.configure({
        baseUrl: appConfigProvider.config.baseUrl,
        clientId: '4aea09da27fdc71b8252d08b04c1fc0c6a5c7cd1',
        clientSecret: 'avai',
        grantPath: 'oauth/access_token'
    });

    //permite trabalhar sem https
    OAuthTokenProvider.configure({
        name: 'token',
        options:{
            secure:false
        }
    })
}]);

app.run(['$rootScope', '$window', 'OAuth', function($rootScope, $window, OAuth) {
    $rootScope.$on('oauth:error', function(event, rejection) {
        // Ignore `invalid_grant` error - should be catched on `LoginController`.
        if ('invalid_grant' === rejection.data.error) {
            return;
        }

        // Refresh token when a `invalid_token` error occurs.
        if ('invalid_token' === rejection.data.error) {
            return OAuth.getRefreshToken();
        }

        // Redirect to `/login` with the `error_reason`.
        return $window.location.href = '/login?error_reason=' + rejection.data.error;
    });
}]);