<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends BASE_Model {

	public function __construct() {
		parent::__construct();

		$this->setTable("tb_usuarios");
	}

	
}

 ?>