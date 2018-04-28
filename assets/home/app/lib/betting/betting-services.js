angular.module("bet", []);

/*
	[
		{idcountry: "1", idleague: "1", idmatch: "1", odd: "odd-1", value: "1.5"},
		{idcountry: "1", idleague: "1", idmatch: "2", odd: "odd-3", value: "11.99"},
		{idcountry: "1", idleague: "2", idmatch: "5", odd: "odd-2", value: "3.75"}
	]
*/

angular.module("bet").directive( // OK
	"betting",
	function() {

		var _betting = [];

		var _bettingController = function ($scope, $element, $attrs) {

			this.print = function () {
				console.log("teste");
			}

			this.registerBetting = function (betting) {
				_betting = betting;
				$scope.betting = betting;
				$scope.$digest();
			};

			this.getBetting = function () {
				return _betting;
			}

			this.showApostas = function () {
				console.log(_betting);
			};

		}

		return {
			controller: _bettingController
		};
	}
);

angular.module("bet").directive( // OK
	"country",
	function () {

		var _country = {};

		var _countryController = function ($scope, $element, $attrs) {

			this.registerLeague = function (league) {

				_country.idcountry = $attrs.idcountry;
				_country.idleague = league.idleague;
				_country.idmatch = league.idmatch;
				_country.odd = league.odd;
				_country.value = match.value;

			};

			this.getCountry = function () {
				return _country;
			};

		};

		return {

			controller: _countryController

		};

	}
);

angular.module("bet").directive( // OK
	"league",
	function() {

		// Compartilhado entre todos os leagues
		// var _leagues = [];

		var _league = {};
		//var _match = [];

		var _leagueController = function($scope, $element, $attrs) {

			this.registerMatch = function(match) {

				_league.idleague = $attrs.idleague;
				_league.idmatch = match.idmatch;
				_league.odd = match.odd;
				_league.value = match.value;

			};

			
			this.getLeague = function() {
				return _league;
			};
			

		}

		return {

			controller: _leagueController

		};
	}
);

angular.module("bet").directive( // OK
	"match",
	function() {

		// Compartilhado entre todos os match
		//var _matches = [];

		var _match = {};


		// Esse é para match individualmente
		var _matchController = function($scope, $element, $attrs) {

			this.registerOdd = function(match) {

				_match.idmatch = $attrs.idmatch;
				_match.odd = match.odd;
				_match.value = match.value;
				
			};

			this.getMatch = function () {
				return _match;
			};

		}


		return {

			controller: _matchController

		};
	}
);

angular.module("bet").directive( // OK
	"odd",
	function() {

		var registerBetting = function (ctrl, country) {

			var betting = ctrl.getBetting();

			var _indexFound = 0;

			if ( betting.some(function(element, index) {
				_indexFound = index;
				return element.idmatch == country.idmatch;
			})) {

				if (
					betting[_indexFound].odd == country.odd
				) {
					betting.splice(_indexFound, 1);
				} else {
					betting[_indexFound] = angular.copy(country);
				}

			} else {
				betting.push(angular.copy(country));
			}

			ctrl.registerBetting(betting);

		};

		var _odd = function(scope, element, attrs, controllers) {

			var matchctrl 		= controllers[0];
			var leaguectrl 		= controllers[1];
			var countryctrl 	= controllers[2];
			var bettingctrl 	= controllers[3];

			var selected = {};

			element.on('click', function(event) {

				selected.odd = attrs.odd;
				selected.value = attrs.value;

				// Pegando a partida
				matchctrl.registerOdd(selected);

				match = matchctrl.getMatch();
				// -----------------------------
				
				// Pegando a liga
				leaguectrl.registerMatch(match);

				league = leaguectrl.getLeague();
				// -----------------------------
				
				// Pegando o país
				countryctrl.registerLeague(league);
				
				country = countryctrl.getCountry();
				// -----------------------------

				// Registrando a aposta
				registerBetting(bettingctrl, country);

				//bettingctrl.registerBetting();

			});

		}

		return {

			restrict: "AE",
			require: ["^match", "^^league", "^^country", "^^betting"],
			link: _odd

		};
	}
);

