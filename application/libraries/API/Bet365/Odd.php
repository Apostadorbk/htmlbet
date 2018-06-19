<?php 
/*
	Está classe fará toda logica que usa ligas e com os arquivos cache
*/
class Odd {

	public function __construct() {
		echo 'Criando um objeto Odd'.'<br>';
	}
	
	public function teste() {

		$p = new Database();

		var_dump( $p );

	}

}

?>