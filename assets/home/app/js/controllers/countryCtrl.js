angular.module('homeApp').controller(
	'countryCtrl', 
	function ($rootScope, $scope, homeConfig, homeAPI) {

		/*
			Buscar as odds dos countries
			Organizar as Odds por competição
		*/

		// Informações de Country
		$scope.main = {
			title: 'Regiões',
			allCountries: [],
			countryAtivos: [],
			qtdAtivos: 50,
			qtdTotal: 0,
			qtdCarregamento: 20
		};

		$scope.selected = {
			country: {},
			actived: false 
		}

		$scope.oneAtATime = true;

		$scope.status = {
			isCustomHeaderOpen: false,
			isFirstOpen: true,
			isFirstDisabled: false
		};

		$scope.leagues = {};


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

	//////////////////////////////////////////////////////////////////////////////////////////
	//// ODDS

		$scope.odd = [];

		$scope.getOddsByCountry = function (country) {

			var dataAtual = new Date();
			
			var send = {
				idcountry: country.idcountry,
				idleagues: []
			};

			country.leagues.forEach(function(element) {
				send.idleagues.push(element.idleague);
			});

			homeAPI.homepost('odd/getbycountry', send).then(function (response){
				console.log( response.data );
			});

		}

	//////////////////////////////////////////////////////////////////////////////////////////

		$scope.selectCountry = function (country) { // OK

			if ( $scope.selected.country.idcountry == country.idcountry ) {
				$scope.selected.country = {};
				$scope.selected.actived = false;
			} else {
				$scope.selected.country = country;
				$scope.selected.actived = true;
				$scope.getOddsByCountry(country);
			}

		};

		$scope.isCountrySelected =  function (country) { // OK
			if ($scope.selected.country.idcountry == country.idcountry) {
				return true;
			} else {
				return false;
			}
		};

		$scope.loadCountry = function () { // OK

			$scope.main.countryAtivos = $scope.main.allCountries.slice(
				0,
				$scope.main.qtdAtivos + $scope.main.qtdCarregamento
			);

			$scope.main.qtdAtivos = $scope.main.qtdAtivos + $scope.main.qtdCarregamento;
			
			if ( $scope.main.qtdTotal <= $scope.main.qtdAtivos ) {
				$scope.main.qtdAtivos = $scope.main.qtdTotal;
			}

			// console.log($scope.main);
		};

		$scope.isCountryActive = function (country) { // OK
			var find = $scope.main.countryAtivos.some(function (element) {
				return country.idcountry == element.idcountry;
			});

			return find;
		};

		$scope.selectLeague = function (league) {
			console.log(league);
		};
});