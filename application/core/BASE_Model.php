<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class BASE_Model extends CI_Model {

	// Dados que vem do BD
	//private $values = [];

	protected $table = '';
	// protected $tables = [];

	public function __construct() {
		parent::__construct();
		
		// Pegando a tabela de constantes
		$this->getLibrary('constant');
	}

	public function setTable($table) {
		$this->table = $table; 
	}
	
	// ---------------------------------------------------------------------
	//	DESCONSIDERAR POR UM MOMENTO 
	/*
	*	Função __call
	*
	*	É uma função mágica que será chamado com a chamada de uma função
	*   não definida na classe
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $name é uma string com o nome da função que está sendo chamado
	*	@param $args é array com os argumentos passado na chamada
	*	
	*/
	/*
	public function __call($name, $args) {

		$method = substr($name, 0, 3);
		$fieldName = substr($name, 3);

		switch ($method) {
			case 'get':
				return $this->values[$fieldName];
			break;

			case 'set':
				$this->values[$fieldName] = $args[0];
			break;
		}

	}
	*/

	/*
	*	Função setData
	*
	*	Ela seta os dados da model em que o valor da chave do parametro
	*	será o mesmo em $values
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $data é um array com a chave que terá o mesmo nome dos campos
	*	das tabelas no DB;
	*
	*/
	/*
	public function setData($data = []) {

		$arrayData = (array) $data[0]; 

		foreach ($arrayData as $key => $value)
			$this->{"set".$key}($value);

	}
	*/

	/*
	*	Função getValues
	*
	*	Retorna todos os valores de $values
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@return é retornado $values;
	*	
	*/
	/*
	public function getValues():array {
		return $this->values;
	}
	*/

	/*
	*	Função getLibrary
	*
	*	Importa uma biblioteca para a model
	*	
	*	@author Bruno	
	*	@package Theo
	*
	*	@param $className recebe o nome da biblioteca a ser importada
	*	
	*/
	protected function getLibrary($className) {
		require_once APPPATH.'libraries/'.ucfirst($className).'.php';
	}
	// ATÉ AQUI
	// ------------------------------------------------------------------------

	/*
	*	Função insertData
	*
	*	Inseri um registro na tabela
	*
	*	@author Bruno
	*	@package Theo
	*
	*	@param $tableName é o nome da tabela
	*	@param $data são os dadosa serem inseridos
	*
	*	@return Se houver algum campo NULL em $data será retornado false,
	*	se todos os dados terem sido validados corretamente e inserido com
	*	sucesso então é retornado o ultimo ID inserido que é maior que zero, 
	*	se nao for inserido com sucesso então é retorna 0;
	*	
	*/
	public function insertData($data = []) {

		if ( empty($this->table) || empty($data) ) {
			return false;
		}

		foreach ($data as $value) {
			if ( !isset($value) ) {
				return false;
			}
		}

		$this->db->query( $this->db->insert_string($this->table, $data) );
		return $this->db->insert_id();
	}

	/*
	*	Função deleteData
	*
	*	Deleta uma linha na tabela padrão de onde ta sendo chamada a model
	*	
	*	@author Bruno
	*	@package Theo
	*
	*	@param $condition é a condição de filtragem das linhas
	*
	*	@return retorna true se foi deletado com sucesso, caso nao tenha encontrado 
	*	nenhuma linha então é retornado false, se algum parametro for
	*	omitido é retornado false tbm;
	*	
	*/
	public function deleteData($condition = []) {

		if ( empty($this->table) || empty($condition) ) {
			return false;
		}

		return $this->db->delete($this->table, $condition);

	}

	/*
	*	Função updateData
	*
	*	Atualiza uma linha na tabela padrão de onde ta sendo chamada a model
	*	
	*	@author Bruno
	*	@package Theo
	*
	*	@param $data redebe os dados a serem atualizados na tabela
	*	@param $condition é a condição de filtragem das linhas
	*
	*	@return retorna true se foi atualizado com sucesso, caso nao tenha encontrado 
	*	nenhuma linha então é retornado false, se  alguns dos dados nao forem setados 
	*	como a tabela e $condition então retorna false, se algum parametro for
	*	omitido é retornado false tbm;;
	*	
	*/
	public function updateData($data = [], $condition = []) {

		if ( empty($this->table) || empty($data) || empty($condition) ) {
			return false;
		}

		foreach ($data as $value) {
			if ( !isset($value) )
				return false;
		}

		return $this->db->update($this->table, $data, $condition);
	}

	/*
	*	Função selectData
	*
	*	Seleciona uma linha na tabela padrão de onde ta sendo chamada a model
	*	
	*	@author Bruno
	*	@package Theo
	*
	*	@param $fields recebe os campos a serem buscados
	*	@param $condition é a condição de filtragem das linhas
	*
	*	@return retorna um array associativo se foi selecionado com sucesso, caso nao 
	*	tenha encontrado é retornado false, se $fields tem algum campo NULL devido 
	*	validação dos dados então é retornado false, se algum parametro for
	*	omitido é retornado false tbm;
	*	
	*/
	public function selectData($fields = [], $condition = []) {

		if ( empty($this->table) || empty($fields) || empty($condition) ) {
			return false;
		}

		if ( is_array($fields) ) {
			foreach ($fields as $value) {
				if ( !isset($value) ) {
					return false;
				}
			}
		}

		$result = $this->db->select($fields)->where($condition)->get($this->table)->result_array();

		if ( !empty($result) ) {
			return $result[0];
		} else {
			return false;
		}

	}

	/*
	*	Função getJoin
	*
	*	Faz uma junção da tabela principal da model com a informada no parametro.
	*
	*	@author bruno
	*	@package Theo
	*
	*	@param $tableName é a tabala onde será feita a junção
	*	@param $fields são os campos que serão selecionados da junção
	*	@param $condition é a condição de filtragem das linhas
	*	#param $matchColumn é a coluna onde será feita o match entre as duas tabelas
	*
	*	@return éretornado um array associativo os campos da junção, porém se nao for
	*	encontrado nenhuma linha então é retornado false, se $fields tem algum campo
	*	NULL devido validação dos dados então é retornado false, se algum parametro for
	*	omitido é retornado false tbm;
	*	
	*/
	public function getJoin($tableName = '',  $fields = [], $condition = [], $matchColumn = '') {

		if ( empty($this->table) || empty($tableName) || empty($fields) || empty($condition) || empty($matchColumn) ) {
			return false;
		}

		if ( is_array($fields) ) {
			foreach ($fields as $value) {
				if ( !isset($value) ) {
					return false;
				}
			}
		}

		$this->db->select($fields)->from($this->table)->join($tableName, $this->table.'.'.$matchColumn.' = '.$tableName.'.'.$matchColumn );
		$result = $this->db->where($condition)->get()->result_array();

		if ( !empty($result) ) {
			return $result[0];
		} else {
			return false;
		}
	}

}

?>