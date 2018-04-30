angular.module("homeApp").controller(
	"homeCtrl", 
	function ($rootScope, $scope, homeConfig, homeAPI) {

		$scope.baseUrl = homeConfig.baseUrl;

});