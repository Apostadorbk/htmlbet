angular.module("adminApp").controller(
	"adminCtrl", 
	function ($rootScope, $scope) {

		$scope.selectItem = function (_item, _title, _description) {
			$scope.page = {
				title: _title,
				description: _description
			};

			$scope.selection = _item;
		};

});