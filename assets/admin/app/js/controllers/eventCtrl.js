angular.module("adminApp").controller(
	"eventCtrl", 
	function ($scope, adminAPI, store) {

		$scope.send = {
			idcountry: '',
			from: '',
			to: ''
		};

		$scope.countries = [];

		$scope.selected = store.new();

		$scope.atualizarPais = function (country) { // OK
			
			$scope.send.idcountry = country.idcountry;

			$scope.insertCountry(country.idcountry, "Atualizando...");
			
			adminAPI.adminpost('event/seteventbycountry', $scope.send).then( function (response) {
				var data = response.data;
				console.log(data);
				$scope.setMessage(country.idcountry, data.message, data.status);
			});
		};

		$scope.carregarPaises = function () { // OK
			adminAPI.adminget('country/get').then( function (response) {
				$scope.countries = response.data;
				console.log( $scope.countries );
			});
		};

		
		$scope.insertCountry = function (idcountry, message) { // OK
			if ( !$scope.selected.hasData(idcountry) ) {
				return $scope.selected.push(idcountry, message);
			}
		};

		$scope.hasCountry = function (idcountry) { // OK
			return $scope.selected.hasData(idcountry);
		};

		$scope.getMessage = function (idcountry) { // OK
			if( $scope.selected.hasData(idcountry) ) {
				var country = $scope.selected.getData();
				return country.message;
			}
		};

		$scope.setMessage = function (data, message, status) {
			$scope.selected.updateMessage(data, message, status);
		};

});