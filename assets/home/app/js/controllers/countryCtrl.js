angular.module('homeApp').controller(
	'countryCtrl', 
	function ($rootScope, $scope, homeConfig, homeAPI, store) {

		/*
			Buscar as odds dos countries
			Organizar as Odds por competição
		*/

		// Informações de Country
		$scope.main = {
			title: 'Regiões',
			allCountries: [],
			countryAtivos: store.new(),
			qtdAtivos: 50,
			qtdTotal: 0,
			qtdCarregamento: 20
		};

		homeAPI.homepost('odd/get',{'country': 'ALL'}).then(function (response) {
			console.log(response.data);
		});

		// Primeiro
		/*
		homeAPI.homeget('country/getall').then(function (response) { // OK
			$scope.main.allCountries = response.data;
			$scope.main.qtdTotal = response.data.length;
			$scope.main.qtdAtivos = ($scope.main.qtdTotal > 30) ? 30 : $scope.main.qtdTotal;

			homeAPI.homeget('league/getleagues').then(function(response) {
				if ( response.data.status ) {

					$scope.leagues = response.data.response;
					
					$scope.main.allCountries.forEach(function (element, index, array) {
					
						array[index].leagues = $scope.leagues.filter(function(league) {
							return league.idcountry == element.idcountry;
						});

					});
					
					$scope.main.countryAtivos = $scope.main.allCountries.slice(0, $scope.main.qtdAtivos);

					//Buscar a odd do primeiro campeonato
					$scope.getOddsByCountry($scope.main.countryAtivos[0]);
				}
			});
			
		});
		*/

	//////////////////////////////////////////////////////////////////////////////////////////
	//// ODDS


	//////////////////////////////////////////////////////////////////////////////////////////


		$scope.loadCountry = function () { // OK

			console.log("Carregando países");

			/*
			$scope.main.countryAtivos = $scope.main.allCountries.slice(
				0,
				$scope.main.qtdAtivos + $scope.main.qtdCarregamento
			);

			$scope.main.qtdAtivos = $scope.main.qtdAtivos + $scope.main.qtdCarregamento;
			
			if ( $scope.main.qtdTotal <= $scope.main.qtdAtivos ) {
				$scope.main.qtdAtivos = $scope.main.qtdTotal;
			}
			*/
			// console.log($scope.main);
		};

});