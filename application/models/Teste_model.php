<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Model.php';

class Teste_model extends Model {

	public function __construct() {
		echo 'Criando um teste model';
	}

	public function teste() {
		$p = new Database();

		$this->setValues( $p->select("SELECT * FROM tb_country") );
	}

}

 ?>