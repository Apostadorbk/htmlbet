<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Vinculo_model extends BASE_Model {

	public function __construct() {
		parent::__construct();

		$this->setTable("tb_vinculos");

		$this->getLibrary('constant');
	}
	
	/*
	*	Função getVinculos
	*
	*	Pega os vinculos do aluno e coloca uam descrição para cada vinculo
	*	no indice "desvinculo"
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $tableName é o nome da tabela
	*	@param $fields é os campos a serem selecionados
	*	@param $condition é a comdição da escolha dos vinculos
	*
	*	@return retorna um array personalizado com os dados do vinculo
	*	
	*/
	public function getVinculos($fields = [], $condition = []) {
		if ( empty($this->table) || empty($fields) || empty($condition) ) {
			return false;
		}

		foreach ($fields as $value) {
			if ( !isset($value) ) {
				return false;
			}
		}

		$data = $this->db->select($fields)->where($condition)->get($this->table)->result_array();

		$values = $data[0];
		$values['intvinculo'] = [];
		$values['intprivilegio'] = [];

		foreach ($data as $value) {

			switch ($value['intvinculo']) {
				
				case Constant::VISITANTE:
					$values['desvinculo'][] = "Visitante";
				break;

				case Constant::ADMINISTRADOR:
					$values['desvinculo'][] = "Administrador";
				break;

				case Constant::PROFESSOR:
					$values['desvinculo'][] = "Professor";
				break;

				case Constant::CONTADOR:
					$values['desvinculo'][] = "Contador";
				break;

				case Constant::ALUNO:
					$values['desvinculo'][] = "Aluno";
				break;

			}

			$values['intvinculo'][] = $value['intvinculo'];
			$values['intprivilegio'][] = $value['intprivilegio'];
		}

		return $values;
	}
}

 ?>