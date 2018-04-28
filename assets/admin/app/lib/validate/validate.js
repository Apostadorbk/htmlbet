angular.module("validate", []);

angular.module("validate").directive(
	"validateEmail",
	function () {

		var _validate = function (scope, element, attr, ctrl) {

			var regexp = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);

			ctrl.$validators.validEmail = function (modelValue, viewValue) {
				var value = modelValue || viewValue;

				return regexp.test(value);
			};

		};

		return {

			require: "ngModel",
			// scope: {},
			link: _validate

		};

	}
);

/*
angular.module("validate").directive(
	"games",
	function () {
		
	}
);

angular.module("validate").directive();

angular.module("validate").directive();
*/