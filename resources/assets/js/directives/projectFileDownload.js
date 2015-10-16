angular.module('app.directives')
    .directive('projectFileDownload',
    ['$timeout','appConfig','ProjectFile',function($timeout,appConfig,ProjectFile){
        return {
            restrict: 'E',
            templateUrl: appConfig.baseUrl + '/build/views/templates/projectFileDownload.html',
           link: function(scope, element, attr){
               var anchor = element.children()[0];
                scope.$on('salvar-arquivo',function(event,data){
                    $(anchor).removeClass('disabled');
                    $(anchor).text('Salvar arquivo');
                    $(anchor).attr({
                        //como o arquivo foi convertido pra base 64 deve ser informado ao navegador para efetuar o download
                        href:'data:application-octet-stream;base64,'+data.file,
                        download: data.name
                    });
                    //chama o evento de click para iniciar o download
                    $timeout(function(){
                        scope.downloadFile = function(){};
                        $(anchor)[0].click();
                    });
                });
           },
           controller: ['$scope','$element','$attrs',
               function($scope,$element,$attrs){
                //pega do scopo a chamda do ng-click donwloadFile
                $scope.downloadFile = function(){
                    //pega o elemento da tela que é o prorpiro <project-file... e busca o filho que deve ser o <a
                    var anchor = $element.children()[0];
                    $(anchor).addClass('disabled');
                    $(anchor).text('carregando...');
                    ProjectFile.download({id:null,idFile: $attrs.idFile},function(data){
                        //como é alteração de dom não deve ser feita na controller, chama a função de link
                        $scope.$emit('salvar-arquivo',data);
                    });

                };
           }]
        };
    }]);