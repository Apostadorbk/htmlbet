<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*	Classe Validate
*
*	É uma classe que valida os dados
*/

class Validate {

	public static function type($data, $type) {

	switch ($type) {
			case 'int':
				return filter_var($data, FILTER_VALIDATE_INT);
			break;

			case 'float':
				return filter_var($data, FILTER_VALIDATE_FLOAT);
			break;

			case 'email':
				return filter_var($data, FILTER_VALIDATE_EMAIL);
			break;

			case 'string':
				$length_before = strlen($data); 

				$charlist = ["\'", "\"", "\\", "\0", "\t", " ", "\n", "\r", "\x0B"];

				$string = addslashes($data);

				$string = str_replace( ["\'", "\"", "\\"], "", $string );

				foreach ($charlist as $value) {
					$string = trim($string, $value);
				}

				$string = strip_tags($string);

				if ( $length_before - strlen($string) )
					return false;

				return $string;
			break;

			case 'ip':
				return filter_var($data, FILTER_VALIDATE_IP);
			break;

			case 'url':
				return filter_var($data, FILTER_VALIDATE_URL);
			break;

			case 'regex':
				return filter_var($data, FILTER_VALIDATE_REGEXP);
			break;
		}

	}

	public static function allTypes($data, $type, $url = "/home") {

		// Se quiser recuperar todos os dados integros
		Session::set([Constant::DATA => $data]);

		$error = [];

		$i = 0;
		foreach ($data as $key => $value) {
			$values[$key] = (!self::type($value, $type[$i])) ? NULL : $value;

			if ( !$values[$key] ) {
				$error[$key] = "O campo ".substr($key, 3)." é inválido!";
			}

			$i++;
		}

		// Setando a sessão de erros de validação
		Session::set([Constant::DATA_VALIDATE => $values]);
		// Setando a sessão de erros de validação
		Session::set([Constant::ERROR_VALIDATE => $error]);

		if ( !empty($error) ) {
			return Redirect::route($url);
		}

	}
	
}

?>