angular.module("listaTelefonica").filter(
	"name",
	function () {

		return function (input) {
			var lista = input.split(" ");

			var lista_formatada = lista.map(function (nome) {

				if ( /(DA|DE|Da|De|dE|dA)/.test(nome) ) {
					return nome.toLowerCase();
				}

				return nome.charAt(0).toUpperCase() + nome.substring(1).toLowerCase();
			});

			//console.log(lista_formatada);
			return lista_formatada.join(" ");
		}

	}
);