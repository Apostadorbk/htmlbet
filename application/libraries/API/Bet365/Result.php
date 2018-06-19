<?php 
/*
	Está classe fará toda logica que usa ligas e com os arquivos cache
*/
class Result {

	public function __construct() {
		echo 'Criando um objeto Result'.'<br>';
	}
	
	public function teste() {

		$p = new Database();

		var_dump( $p );

	}

}

?>