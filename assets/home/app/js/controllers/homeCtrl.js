angular.module("homeApp").controller(
	"homeCtrl", 
	function ($rootScope, $scope, homeConfig, homeAPI) {

		// $scope.baseUrl = homeConfig.baseUrl;

		// Lista de todos os países
		$scope.countries = {}

		// Títulos das partes
		$scope.title = {
			country: 'Regiões'
		};


		homeAPI.homeget('country/getall').then(function (response) {
			$scope.countries = response.data;
			console.log($scope.countries);
		});

		//console.log( homeAPI.homeget('country/getall') );

});