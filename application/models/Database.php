<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Database {

	protected $pdo;

	private $results;


	public function __construct() {
		$this->connect();
	}


	// Pegando as informações e criando a conexão DB
	private function connect() {

		$host 		= Constant::HOST;
		$dbname 	= Constant::DBNAME;
		$user 		= Constant::USER;
		$password 	= Constant::PASSWORD;

		$mysql = "mysql:host=".$host.";dbname=".$dbname;

		try {
			// Criando a conexão com o DB
			$this->pdo = new PDO($mysql, $user, $password, [
        		PDO::ATTR_PERSISTENT => true 
        	]);

		} catch (PDOException $e) {
			echo $e->getMessage();
		}

	}

	// Setando os parametros
	private function setParams($statement, $parameters = array()) {
		foreach ($parameters as $key => $value) {
			$this->bindParam($statement, $key, $value);
		}

		return;
	}

	// Fazendo o bind dos parametros do statment
	private function bindParam($statement, $key, $value) {
		return $statement->bindParam($key, $value);
	}

	// Consulta que não retorna nada
	protected function query($rawQuery, $params = array()):bool {

		$stmt = $this->pdo->prepare($rawQuery);
		$this->setParams($stmt, $params);
		$success = $stmt->execute();
		$stmt->closeCursor();
		return $success;

	}

	// Operação que precisa retorna resultados do DB
	protected function select($rawQuery, $params = array()) {
		
		$stmt = $this->pdo->prepare($rawQuery);
		$this->setParams($stmt, $params);
		$stmt->execute();
		$results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		return $results;
		
	}

	protected function encoded($array, $keyEncoded) {

		$encoded = $array;

		foreach ($encoded as $key => $value) {
			$encoded[$key][$keyEncoded] = utf8_encode($value[$keyEncoded]);
		}

		return $encoded;

	}

	protected function decoded($data, $keyEncoded = NULL) {

		if ( !isset($keyEncoded) ) {
			return utf8_decode($data);
		}

		$decoded = $array;

		foreach ($decoded as $key => $value) {
			$decoded[$key][$keyEncoded] = utf8_decode($value[$keyEncoded]);
		}

		return $decoded;

	}

}

 ?>