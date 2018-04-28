angular.module("ui", []);

angular.module("ui").run(function ($templateCache) {

	$templateCache.put("view/accordion.html", 
	'<div class="ui-accordion-title" ng-click="open()">{{title}}</div><div class="ui-accordion-content" ng-show="isOpened" ng-transclude></div>'
	);

});

angular.module("ui").directive(
	"uiAccordions",
	function () {

		var idCountryOpened = '';

		return {

			controller: function ($scope, $element, $attrs) {

				var children = [];

				this.teste = function () {

				}

				this.register = function (children) {

				}

				this.getCountryOpen = function () {
					if ( idCountryOpened ) {
						return idCountryOpened;
					}
				};

				this.setChild = function (child) {
					children.push(child);
				}
			}

		};
	}
);

angular.module("ui").directive(
	"uiAccordion",
	function () {

		return {

			// templateUrl: "view/accordion.html",
			restrict: "AE",
			require: "^uiAccordions",
			link: function (scope, element, attrs, ctrl) {

				// ctrl.registerAccordion(scope);

				/*
				scope.open = function () {
					ctrl.closeAll();
					// Mudando de true para false e false para true
					scope.isCountryOpened = true;
				};
				*/
				
				/*
				scope.open = function (id) {
					if ( id == scope.idCountryOpened )
				}
				*/
				
				element.on("click", function(event) {

					element["0"].children[1].hidden = !element["0"].children[1].hidden;



					//ctrl.setCountryOpen(attrs.idcountry);

					// console.log(element["0"]);
					//console.log(attrs);

				});
				
				
			}

		};
	}
);

angular.module("ui").directive(
	"uiAlert",
	function () {

		return {

			// template: "<div>Alerto inserido com sucesso!</div>",
			templateUrl: "view/alert.html",
			replace: true,
			restrict: "AE",
			scope: {
				title: "@"
			},
			transclude: true

		};
});

angular.module("ui").directive(
	"uiDate",
	function ($filter) {

		return {

			require: "ngModel",
			link: function (scope, element, attrs, ctrl) {

				var _formatDate = function (date) {

					date = date.replace(/[^0-9]+/g, "");

					if (date.length > 2) {
						date = date.substring(0,2) + "/" + date.substring(2);
					}

					if (date.length > 5) {
						date = date.substring(0,5) + "/" + date.substring(5,9);
					}

					return date;
				};

				element.bind("keyup", function () {
					ctrl.$setViewValue(_formatDate(ctrl.$viewValue));
					ctrl.$render();
				});

				ctrl.$parsers.push(function (value) {
					if ( value.length == 10 ) {
						var dateArray = value.split("/");
						return new Date(dateArray[2], dateArray[1]-1, dateArray[0]).getTime();
					}
				});

				ctrl.$formatters.push(function (value) {
					return $filter("date")(value, "dd/MM/yyyy");
				});
			}

		};
	}
);