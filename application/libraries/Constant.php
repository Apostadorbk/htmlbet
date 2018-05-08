<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*	Classe Constant
*	
*	É uma classe que contém as constantes
*	
*/

define("_TINYINT", 1);
define("_SMALLINT", 2);
define("_MEDIUMINT", 3);
define("_INT", 4);
define("_BIGINT", 8);
define("_DATETIME", NULL);
define("_TIMESTAMP", NULL);

define("PK", 'PRIMARY_KEY');
define("FK", 'FOREIGN_KEY');

define("USUARIOS", 'tb_usuarios');
define("VINCULOS", 'tb_vinculos');
define("ENDERECOS", 'tb_enderecos');
define("SENHASRECUPERADAS", 'tb_senhasrecuperadas');

class Constant {

	const APIKEY = '781f3b505bca14bccc0fe5211401d43e25d6d07eeb40f2411377755ed228598b';

	// Banco de dados
	const HOST = "localhost";
	const DBNAME = "db_betting";
	const USER = "root";
	const PASSWORD = "";

	const TYPES = [
		'tinyint' => 1,
		'smallint' => 2,
		'mediumint' => 3,
		'int'	=> 4,
		'bigint' => 8
	];


/*
	['tipo' => '', 'tamanho' => '']

	'' => ['tipo' => '', 'tamanho' => '']

	'PK' => ['campo' => ''],
	'FK' => ['campo' => [], 'table' => []],

	''		=> [
		'tipo' => '', 'tamanho' => '', 'atributo' => '', 'default' => 'NONE'
	],


		'' => [
			'PK' => ['campo' => ''],
			'FK' => NULL,
			'FK' => ['campo' => [], 'table' => []],
			// campos
			''		=> [
				'tipo' => '', 'tamanho' => '', 'atributo' => '', 'default' => 'NONE'
			],
			''		=> [
				'tipo' => '', 'tamanho' => '', 'atributo' => '', 'default' => 'NONE'
			]
		]

*/

// ---------------------------------------------------------------------------------------
// CONSTANTES DAS TABLEAS
// ---------------------------------------------------------------------------------------

	const TABLE_NAMES = [
		USUARIOS, VINCULOS, ENDERECOS, SENHASRECUPERADAS
	];

	const TABLES_FIELDS = [
		USUARIOS => [
			'idusuario', 
			'desnome', 
			'dessobrenome', 
			'deslogin', 
			'dessenha', 
			'desemail',
			'inttelefone',
			'intativo',
			'dteultimologin',
			'dteregistro'
		],

		VINCULOS => [
			'idvinculo',
			'idusuario',
			'intvinculo',
			'intprivilegio',
			'dteadmissao',
			'dteregistro'
		]
	];

	const TABLE = [
		USUARIOS => [
			PK => ['campo' => 'idusuario'],
			FK => NULL,
			'idusuario'		=> [
				'tipo' => 'int', 'tamanho' => '4', 'atributo' => 'unsigned', 'default' => 'NONE'
			],
			'desnome'		=> ['tipo' => 'varchar', 'tamanho' => '32', 'atributo' => NULL, 'default' => 'NONE'],
			'dessobrenome'	=> ['tipo' => 'varchar', 'tamanho' => '32', 'atributo' => NULL, 'default' => 'NONE'],
			'deslogin'		=> ['tipo' => 'varchar', 'tamanho' => '32', 'atributo' => NULL, 'default' => 'NONE'],
			'dessenha'		=> ['tipo' => 'varchar', 'tamanho' => '72', 'atributo' => NULL, 'default' => 'NONE'],
			'desemail'		=> ['tipo' => 'varchar', 'tamanho' => '64', 'atributo' => NULL, 'default' => 'NONE'],
			'inttelefone'	=> ['tipo' => 'bigint', 'tamanho' => '8', 'atributo' => NULL, 'default' => 'NULL'],
			'intativo' 		=> ['tipo' => 'tinyint', 'tamanho' => '1', 'atributo' => 'UNSIGNED', 'default' => '1'],
			'dteultimologin'=> ['tipo' => 'datetime', 'tamanho' => _DATETIME, 'atributo' => NULL, 'default' => 'NULL'],
			'dteregistro' 	=> [
				'tipo' => 'timestamp', 'tamanho' => _TIMESTAMP, 'atributo' => NULL, 'default' => 'CURRENT_TIMESTAMP'
			]
		],

		VINCULOS => [
			PK => ['campo' => 'idvinculo'],
			FK => [
				'campo' => ['idusuario'], 
				'table' => ['idusuario' => 'tb_usuarios']
			],
			// campos
			'idvinculo'		=> [
				'tipo' => 'int', 'tamanho' => _INT, 'atributo' => 'UNSIGNED', 'default' => 'NONE'
			],
			'idusuario'		=> [
				'tipo' => 'int', 'tamanho' => _INT, 'atributo' => 'UNSIGNED', 'default' => 'NONE'
			],
			'intvinculo'	=> [
				'tipo' => 'smallint', 'tamanho' => _SMALLINT, 'atributo' => 'UNSIGNED', 'default' => '1'
			],
			'intprivilegio'	=> [
				'tipo' => 'smallint', 'tamanho' => _SMALLINT, 'atributo' => 'UNSIGNED', 'default' => '1'
			],
			'dteadmissao'	=> [
				'tipo' => 'datetime', 'tamanho' => _DATETIME, 'atributo' => NULL, 'default' => 'NULL'
			],
			'dteregistro' 	=> [
				'tipo' => 'timestamp', 'tamanho' => _TIMESTAMP, 'atributo' => NULL, 'default' => 'CURRENT_TIMESTAMP'
			]
		]

	];


// ---------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------


















	// Rota de redirecionamento de $_SERVER
	const REDIRECT_URL = 'REDIRECT_URL';
	const LAST_URL = 'LAST_URL';

	// Vínculos
	const VINCULO = [

		'CAMBISTA' 	=> [
			'CODIGO' 	=> 1,
			'DESCRICAO' => 'Cambista'
		],

		'GERENTE' => [
			'CODIGO' 	=> 10,
			'DESCRICAO' => 'Gerente'
		],

		'ADMINISTRADOR' => [
			'CODIGO' 	=> 20,
			'DESCRICAO' => 'Administrador'
		]

	];
	
	const TIPO_VINCULO = "intvinculo"; // Usado em Auth::checkVinculo()
	const TIPO_PRIVILEGIO = "intprivilegio"; // Usado em Auth::checkVinculo()
	//const VINCULO = "intvinculo";
	// mais...



	// Classe Auth e sessão setada
	const USUARIO = 'usuario';
	const IDUSUARIO = 'idusuario';

	// Dados
	const DATA = 'data';

	// Validação
	const DATA_VALIDATE = 'dataValidate';
	const ERROR_VALIDATE = 'errorValidate';

	// Autenticação do usuário
	const ERROR_LOGIN = 'errorLogin';

}


 ?>