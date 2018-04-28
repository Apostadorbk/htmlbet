angular.module("listaTelefonica").factory("contatosAPI", function ($http, config) {

	var _getContatos = function () {
		return $http.get(config.baseUrl+'request/contatos.php');
	};

	var _postContatos = function (contato) {
		return $http.post(config.baseUrl+'request/contatos.php', contato);
	};



	return {
		getContatos: _getContatos,
		postContatos: _postContatos
	};

});