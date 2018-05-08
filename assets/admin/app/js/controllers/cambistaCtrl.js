angular.module("adminApp").controller(
	"cambistaCtrl", 
	function ($scope, $filter, adminAPI) {

		$scope.contatos = [];

		$scope.adicionarContato = function (contato) {

			delete $scope.replySaveContatos;

			$scope.contatos.push(angular.copy(contato));
			delete $scope.contato;
			$scope.cadastro.$setPristine();

		};

		$scope.apagarContato = function(contatos) {

			$scope.contatos = contatos.filter(function (contato) {
				if (!contato.selecionado) return contato;
			});

		};

		$scope.isContatoSelecionado = function (contatos) {

			return contatos.some(function (contato) {
				return contato.selecionado;
			});

		};

		$scope.hasContato = function(contatos) {

			if ( contatos.length > 0 )
				return true;
			else
				return false;

		};

		$scope.saveContatos = function(contatos) {

			var precadastros = [];

			contatos.filter(function (contato) {
				precadastros.push({'email':contato.email});
			});

			adminAPI.adminpost('precadastro', precadastros).then(function (response) {
				
				$scope.contatos = [];
				$scope.replySaveContatos = response.data;

				if ( $scope.replySaveContatos.data ) {
					$scope.contatos = $scope.replySaveContatos.data;
				}
				
				console.log(response.data);
			});


		};

});