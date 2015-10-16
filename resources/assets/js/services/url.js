angular.module('app.services')
    .service('Url',['$interpolate',function($interpolate){
        return {
            getUrlFromUrlSymbol: function(url,params){
                var urlMode = $interpolate(url)(params);
                // evita project//file logo gaz // virar /
                return urlMode.replace(/\/\//g,'/')
                    //retira a ultima barra project/file/ fica project/file
                    .replace(/\/$/,'');
            },
            getUrlResource: function(url){
                //parametro 'g' em todas strings 'global'
                return url.replace( new RegExp('{{','g'),':')
                    .replace(new RegExp('}}','g'),'');
            }
        };
    }]);