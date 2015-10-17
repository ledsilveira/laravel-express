angular.module('app.directives')
    .directive('loadTemplate',
    ['$compile','$http','OAuth',function($compile,$http,OAuth){
        return {
            restrict: 'E',
            link: function(scope, element, attr){
                //quando usuario muda rota, verifica se ta autenticado, se sim atribui o html (menu e afins)
                scope.$on('$routeChangeStart', function(event,next,current){
                    if(OAuth.isAuthenticated()) {
                        if (next.$$route.originalPath != '/login' && next.$$route.originalPath != '/logout') {
                            if (!scope.isTemplateLoad) {
                                //pra evitar que a cada carregamento de outra rota carregue o template novamente
                                scope.isTemplateLoad = true;
                                $http.get(attr.url).then(function (response) {
                                    element.html(response.data);
                                    $compile(element.contents())(scope);
                                });
                            }
                            return;
                        }
                    }
                    resetTemplate();

                    function resetTemplate(){
                        scope.isTemplateLoad = false;
                        element.html("");
                    }
                });

            }
        };
    }]);