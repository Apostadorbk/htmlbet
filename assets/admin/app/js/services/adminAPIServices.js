angular.module("adminApp").factory("adminAPI", function ($http, adminConfig) {


	var _post = function (action, item) {
		return $http.post(adminConfig.baseUrl+action, item);
	};

	var _get = function (action) {
		return $http.get(adminConfig.baseUrl+action);
	};


	return {

		adminpost: _post,
		adminget: _get

	};

});