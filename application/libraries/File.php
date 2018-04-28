<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

define('BASEDIR', $_SERVER['DOCUMENT_ROOT']);

/*
*	Classe File
*
*	É uma classe os arquivos
*/

class File {

	public static function check($directory = '') {
		
		if (  empty($directory) )
			return false;

		$dir = BASEDIR.'/'.$directory;

		if ( !file_exists( $dir ) ) {

			return false;

		} else {

			return true;

		}

	}

	public static function createFolder($directory = '') {
		
		if ( empty($directory) ) 
			return false;

		if ( self::check($directory) )
			return $directory;

		// O caminho relativo que vai sendo criado
		$baseRelative = BASEDIR;
		
		$folders = explode('/', $directory);


		foreach ($folders as $folder) {

			$baseRelative .= '/'.$folder;
			
			if ( !file_exists( $baseRelative ) ) {
				if ( !mkdir( $baseRelative ) ) {
					return false;
				}
			}
			
		}

		return $directory;
		
	}
	
	public static function deleteFile($directory = '') {

		if ( empty($directory) )
			return false;

		$dir = BASEDIR.'/'.$directory;

		if ( !is_file($dir) )
			return false;

		return unlink($dir);

	}

	public static function upload($directory = '', $index = '') {


		if ( empty($directory) || empty($index) )
			return false;

		if ( !isset($_FILES[$index]) )
				return false;

		// Pegando da informação dos arquivos temporários
		$type = gettype( $_FILES[$index]['tmp_name'] );


		if ( $type == 'string' ) { // Único arquivo
			
			//----------------------------------------------------
			// VALIDANDO SE $_FILES TEM ARQUIVO

			if ( empty(($_FILES[$index]['tmp_name'])) )
				return false;

			//----------------------------------------------------
			// CRIANDO A PASTA PARA ARMAZENAMENTO

			$dir = self::createFolder($directory);

			if ( !$dir ) // Se a pasta nao foi criada com sucesso
				return false;

			//----------------------------------------------------
			// CHECANDO SE O ARQUIVO JA EXISTE

			$dir = $dir.'/'.$_FILES[$index]['name'];

			if ( self::check($dir) ) { // Se o arquivo ja existir

				Session::set([ Constant::ALERT_FILE 	=> [ 
					$_FILES[$index]['name'] => "Arquivo ".$_FILES[$index]['name']." já existente!"
				] ]);

				return $dir;
			}


			//----------------------------------------------------
			// MOVENDO O ARQUIVO

			$pathFile = BASEDIR.'/'.$dir;

			if ( move_uploaded_file($_FILES[$index]['tmp_name'], $pathFile) ) { // Se foi movido com sucesso

				Session::set([ Constant::SUCCESS_FILE	=> [ 
					$_FILES[$index]['name'] => "Arquivo ".$_FILES[$index]['name']." movido com sucesso!"
				] ]);

				return $dir;

			} else { // Se não foi movido com sucesso

				Session::set([ Constant::ERROR_FILE	=> [ 
					$_FILES[$index]['name'] => "Falha ao enviar o arquivo ".$_FILES[$index]['name']
				] ]);

				return false;

			}
			

		} else if ( $type == 'array' ) { // Multiplos arquivos

			//----------------------------------------------------
			// VALIDANDO SE $_FILES TEM ARQUIVO

			if ( empty( $_FILES[$index]['tmp_name'][0] ) )
				return false;

			//----------------------------------------------------
			// CRIANDO A PASTA PARA ARMAZENAMENTO

			$dir = self::createFolder($directory);

			if ( !$dir ) // Se a pasta nao foi criada com sucesso
				return false;

			$error = [];
			$success = [];
			$alert = [];
			$listFileDir = [];

			for ($i = 0; $i < count($_FILES[$index]['tmp_name']); $i++) { 
				
				$fileDir = $dir.'/'.$_FILES[$index]['name'][$i];

				$listFileDir[] = $fileDir;

				if ( !self::check($fileDir) ) {

					$pathFile = BASEDIR.'/'.$fileDir;

					if ( move_uploaded_file($_FILES[$index]['tmp_name'][$i], $pathFile) ) { // Se foi movido com sucesso

						$success[$_FILES[$index]['name'][$i]] = "Arquivo ".$_FILES[$index]['name'][$i]." movido com sucesso!";

					} else { // Se não foi movido com sucesso

						$error[$_FILES[$index]['name'][$i]] = "Falha ao enviar o arquivo ".$_FILES[$index]['name'][$i];

					}

				} else { // Se o arquivo ja existe
					$alert[$_FILES[$index]['name'][$i]] = "Arquivo ".$_FILES[$index]['name'][$i]." já existente!";
				}

			}


			if ( !empty($error) ) {

				Session::set([Constant::ERROR_FILE => $error]);
				return false;

			}
			
			if ( !empty($success) ) {

				Session::set([Constant::SUCCESS_FILE => $success]);

			} 

			if ( !empty($alert) ) {

				Session::set([Constant::ALERT_FILE => $alert]);

			}


			return $listFileDir;
		}


	}
	
}

?>