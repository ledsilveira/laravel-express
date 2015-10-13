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
    '$routeProvider','$httpProvider','OAuthProvider','OAuthTokenProvider',
    'appConfigProvider',
    function($routeProvider,$httpProvider,OAuthProvider,OAuthTokenProvider,appConfigProvider){
        //sobrescrevendo o que exist no resource (transformer dos dados)
        //somente quando for retorno em json e com o objeto data
        $httpProvider.defaults.transformResponse = function(data,headers) {
            //pega o conteudo que esta em data e retorna
            //verifica se o header é json
            var headresGetter = headers();
            if(headresGetter['content-type'] == 'application/json' ||
                headresGetter['content-type'] == 'text/json')
            {
                var dataJson = JSON.parse(data);
                //verifica se veio propriedade data
                if( dataJson.hasOwnProperty('data')){
                    dataJson = dataJson.data;
                }
                return dataJson;
            }
            return data;
        };
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
        .when('/client/new',{
            templateUrl:'build/views/client/new.html',
            controller:'ClientNewController'
        })
        .when('/client/:id/show',{
            templateUrl:'build/views/client/listone.html',
            controller:'ClientEditController'
        })
        .when('/client/:id/edit',{
            templateUrl:'build/views/client/edit.html',
            controller:'ClientEditController'
        })
        .when('/client/:id/remove',{
            templateUrl:'build/views/client/remove.html',
            controller:'ClientRemoveController'
        })
        .when('/project/:project_id/notes',{
            templateUrl:'build/views/project-note/list.html',
            controller:'ProjectNoteCrudController'
        })
        .when('/project/:project_id/notes/:idnote/show',{
            templateUrl:'build/views/project-note/listone.html',
            controller:'ProjectNoteEditController'
        })
        .when('/project/:project_id/notes/new',{
            templateUrl:'build/views/project-note/new.html',
            controller:'ProjectNoteNewController'
        })
        .when('/project/:project_id/notes/:idnote/edit',{
            templateUrl:'build/views/project-note/edit.html',
            controller:'ProjectNoteEditController'
        })
        .when('/project/:project_id/notes/:idnote/remove',{
            templateUrl:'build/views/project-note/remove.html',
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