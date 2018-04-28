<?php 

class Redirect {

	/*
	*	Função route
	*
	*	Redireciona para a rota e pode-se mandar algumas mensagens via sessão
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $url é a url onde a página será redirecionado
	*	@param $msgs é um array de mensagens de dados para que seja setado na sessão
	*/
	public static function route($url, $msgs = []) {

		if ( !empty($msgs) )
			Session::set($msgs);
		
		return header( "Location: ".$url );
	}

	/*
	*	Função isPost
	*
	*	Verifica se a requisição é do  tipo POST
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@return é retornado true se sor requissição do tipo post,
	*	e false caso contrário
	*
	*/
	public static function isPost() {
		if ( $_SERVER['REQUEST_METHOD'] === "POST" ) {
			return true;
		} 

		return false;
	}

	public static function fromURL($url, $type) {

		if ( 
			Session::get([Constant::REDIRECT_URL]) == $url
			&&
			$_SERVER['REQUEST_METHOD'] === strtoupper($type)
		) {
			return true;
		}

		return false;

	}

	public static function updateURL() {

		if ( isset($_SERVER[Constant::REDIRECT_URL]) ) {
			Session::set(
				[ Constant::REDIRECT_URL => $_SERVER[Constant::REDIRECT_URL] ]
			);
		}

	}

}

?>