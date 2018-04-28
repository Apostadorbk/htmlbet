angular.module("homeApp").factory("homeAPI", function ($http, homeConfig) {


	var _post = function (action, item) {
		return $http.post(homeConfig.baseHome+action, item);
	};

	var _get = function (action) {
		return $http.get(homeConfig.baseHome+action);
	};


	return {

		homepost: _post,
		homeget: _get

	};

});