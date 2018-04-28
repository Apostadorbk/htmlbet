angular.module("adminApp").config(function($routeProvider, $locationProvider)
{
      // remove o # da url
      $locationProvider.html5Mode({
         enabled: true,
         requireBase: true
      });

   $routeProvider

   // para a rota '/', carregaremos o template home.html e o controller 'HomeCtrl'
   .when('/administrador/teste', {
      // templateUrl : 'teste'
      template: "Teste"
   });

});