<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
*	Classe Auth
*
*	classe responsavel de manipular quem tá autenticado
*/
class APICountry {
	
	const COUNTRY = [
		'163'	=> 'Liga dos Campeões',
		'164'	=> 'Liga da Europa',
		'165'	=> 'UEFA',
		'166'	=> 'Copa América',
		'167'	=> 'Copa das Nações',
		'168'	=> 'Internacional',
		'169'	=> 'Inglaterra',
		'170'	=> 'Itália',
		'171'	=> 'Espanha',
		'172'	=> 'Alemanha',
		'173'	=> 'França',
		'174'	=> 'Holanda',
		'175'	=> 'Bélgica',
		'176'	=> 'Portugal',
		'177'	=> 'Escócia',
		'178'	=> 'Copa do Mundo Sub-20',
		'179'	=> 'Copa do Mundo Sub-17',
		'180'	=> 'Euro Sub-21',
		'181'	=> 'Euro Sub-19',
		'182'	=> 'Euro Sub-17',
		'183'	=> 'Copa do Mundo Feminino',
		'184'	=> 'Euro Feminino',
		'185'	=> 'Austria',
		'186'	=> 'Chipre',
		'187'	=> 'Dinamarca',
		'188'	=> 'Filândia',
		'189'	=> 'Grécia',
		'190'	=> 'Islândia',
		'191'	=> 'Irlanda',
		'192'	=> 'Luxemburgo',
		'193'	=> 'Noruega',
		'194'	=> 'Irlanda do Norte',
		'195'	=> 'Suécia',
		'196'	=> 'Suíça',
		'197'	=> 'Turquia',
		'198'	=> 'País de Gales',
		'199'	=> 'Bielorrússia',
		'200'	=> 'Bósnia e Herzegovina',
		'201'	=> 'Bulgária',
		'202'	=> 'Croácia',
		'203'	=> 'República Checa',
		'204'	=> 'Estônia',
		'205'	=> 'Hungria',
		'206'	=> 'Israel',
		'207'	=> 'Letônia',
		'208'	=> 'Lituânia',
		'209'	=> 'República da Macedônia',
		'210'	=> 'Moldávia',
		'211'	=> 'Montenegro',
		'212'	=> 'Polônia',
		'213'	=> 'Romania',
		'214'	=> 'Rússia',
		'215'	=> 'Sérvia',
		'216'	=> 'Eslováquia',
		'217'	=> 'Eslovênia',
		'218'	=> 'Ucrânia',
		'219'	=> 'América do Sul',
		'220'	=> 'Argentina',
		'221'	=> 'Bolívia',
		'222'	=> 'Brasil',
		'223'	=> 'Chile',
		'224'	=> 'Colômbia',
		'225'	=> 'Equador',
		'226'	=> 'Paraguai',
		'228'	=> 'Uruguai',
		'229'	=> 'Venezuela',
		'231'	=> 'México',
		'232'	=> 'Estados Unidos',
		'233'	=> 'Costa Rica',
		'234'	=> 'El Salvador',
		'235'	=> 'Guatemala',
		'236'	=> 'Honduras',
		'239'	=> 'China',
		'240'	=> 'Índia',
		'242'	=> 'Japão',
		'243'	=> 'República da Coreia',
		'244'	=> 'Singapura',
		'245'	=> 'Tailândia',
		'247'	=> 'Arménia',
		'248'	=> 'Azerbaijão',
		'249'	=> 'Geórgia',
		'250'	=> 'Cazaquistão',
		'252'	=> 'Irã',
		'253'	=> 'Austrália',
		'254'	=> 'Nova Zelândia',
		'256'	=> 'Argélia',
		'257'	=> 'Egito',
		'259'	=> 'Marrocos',
		'260'	=> 'África do Sul',
		'261'	=> 'Tunísia',
		'262'	=> 'Albânia',
		'264'	=> 'Ilhas Feroe',
		'267'	=> 'Malta',
		'272'	=> 'Canadá',
		'277'	=> 'Jamaica',
		'285'	=> 'Hong Kong',
		'287'	=> 'Jordânia',
		'289'	=> 'Malásia',
		'293'	=> 'Catar',
		'294'	=> 'Arábia Saudita',
		'296'	=> 'Emirados Árabes',
		'299'	=> 'Uzbequistão',
		'336'	=> 'Uganda',
		'340'	=> 'Copa do Mundo'
	];

	public static function country() { // OK

		$APIkey = Constant::APIKEY;

		$curl_options = array (
			CURLOPT_URL => "https://apifootball.com/api/?action=get_countries&APIkey=$APIkey",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => false,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_CONNECTTIMEOUT => 5
		);                              
		 
		$curl = curl_init();
		curl_setopt_array( $curl, $curl_options );
		$result = curl_exec( $curl );

		$result = (array) json_decode($result);

		return $result;

	}

	/*
	public static function getAll($decode = false) { // OK

		if ( $decode ) {
			$decoded = [];

			foreach (self::$_COUNTRY as $key => $value) {
				$decoded[$key] = utf8_decode($value);
			}

			return $decoded;
		} else {
			return self::$_COUNTRY;
		}

	}
	*/

	/*
	public static function getById($index, $decode = false) { // OK
		if ( $decode )
			return utf8_decode(self::$_COUNTRY[$index]);
		else 
			return self::$_COUNTRY[$index];
	}
	*/

	public static function check() {

		$all_countries = self::country();
		$format = [];

		foreach ($all_countries as $value) {
			$format[$value->country_id] = $value->country_name;
		}

		$diff = array_diff_key($format, self::COUNTRY);
		
		return $diff;

	}

}

?>