<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*	Classe root 
*
*	 Serve para redirecionar para o home onde é o inicio do site
*/

class Root extends BASE_Controller {

	public function __construct() {
		parent::__construct("Root");

		$this->getLibrary(['Redirect']);
	}

	public function index() {
		Redirect::route("/home");
	}

}
