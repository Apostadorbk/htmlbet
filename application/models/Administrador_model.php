<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__.'\Database.php';


//use Database;

class Administrador_model extends Database {

	public function __construct() {
		parent::__construct();

		//require_once 'Database.php';
	}

	public function teste() {
		return $this->select("SELECT * FROM tb_usuarios");
	}

	public function precadastro($data) {

		$query = "INSERT INTO tb_usuarios (desemail) VALUES ";
		$values = [];
		$index = 0;

		for($i = 0; $i < count($data); $i++) {
			
			$query .= "(:desemail".$i.")";

			if ( $i == (count($data) - 1)  ) {
				$query .= ";";
			} else {
				$query .= ",";
			}

			$values[":desemail".$i] = $data[$i]['email'];

		}
		
		return $this->query( $query, $values );

	}

	public function setCountry() {

		
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
		
		//$results = [];

		


		//$translate = [];
		
		/*
		foreach ($result as $key => $value) {
			
			switch ($value->country_name) {
				case 'Champions League':
					$translate[$key] = utf8_decode('Liga dos Campeões');
				break;

				case 'Europa League':
					$translate[$key] = utf8_decode('Liga da Europa');
				break;

				case 'UEFA':
					$translate[$key] = utf8_decode('UEFA');
				break;

				case 'Copa America':
					$translate[$key] = utf8_decode('Copa América');
				break;

				case 'Cup of Nations':
					$translate[$key] = utf8_decode('Copa das Nações');
				break;
				
				case 'International':
					$translate[$key] = utf8_decode('International');
				break;

				case 'England':
					$translate[$key] = utf8_decode('Inglaterra');
				break;

				case 'Italy':
					$translate[$key] = utf8_decode('Itália');
				break;

				case 'Spain':
					$translate[$key] = utf8_decode('Espanha');
				break;

				case 'Germany':
					$translate[$key] = utf8_decode('Alemanha');
				break;

				case 'France':
					$translate[$key] = utf8_decode('França');
				break;

				case 'Netherlands':
					$translate[$key] = utf8_decode('Holanda');
				break;

				case 'Belgium':
					$translate[$key] = utf8_decode('Bélgica');
				break;

				case 'Portugal':
					$translate[$key] = utf8_decode('Portugal');
				break;

				case 'Scotland':
					$translate[$key] = utf8_decode('Escócia');
				break;

				case 'World Cup U-20':
					$translate[$key] = utf8_decode('Copa do Mundo Sub-20');
				break;

				case 'World Cup U-17':
					$translate[$key] = utf8_decode('Copa do Mundo Sub-17');
				break;

				case 'Euro U-21':
					$translate[$key] = utf8_decode('Euro Sub-21');
				break;

				case 'Euro U-19':
					$translate[$key] = utf8_decode('Euro Sub-19');
				break;

				case 'Euro U-17':
					$translate[$key] = utf8_decode('Euro Sub-17');
				break;

				case 'World Cup Women':
					$translate[$key] = utf8_decode('Copa do Mundo Feminino');
				break;

				case 'Euro Women':
					$translate[$key] = utf8_decode('Campeonato Europeu Feminino');
				break;

				case 'Austria':
					$translate[$key] = utf8_decode('Austria');
				break;

				case 'Cyprus':
					$translate[$key] = utf8_decode('Chipre');
				break;

				case 'Denmark':
					$translate[$key] = utf8_decode('Dinamarca');
				break;

				case 'Finland':
					$translate[$key] = utf8_decode('Filândia');
				break;

				case 'Greece':
					$translate[$key] = utf8_decode('Grécia');
				break;

				case 'Iceland':
					$translate[$key] = utf8_decode('Islândia');
				break;

				case 'Ireland':
					$translate[$key] = utf8_decode('Irlanda');
				break;

				case 'Luxembourg':
					$translate[$key] = utf8_decode('Luxemburgo');
				break;

				case 'Norway':
					$translate[$key] = utf8_decode('Noruega');
				break;

				case 'Northern Ireland':
					$translate[$key] = utf8_decode('Irlanda do Norte');
				break;

				case 'Sweden':
					$translate[$key] = utf8_decode('Suécia');
				break;

				case 'Switzerland':
					$translate[$key] = utf8_decode('Suíça');
				break;

				case 'Turkey':
					$translate[$key] = utf8_decode('Turquia');
				break;

				case 'Wales':
					$translate[$key] = utf8_decode('País de Gales');
				break;

				case 'Belarus':
					$translate[$key] = utf8_decode('Bielorrússia');
				break;

				case 'Bosnia & Herz.':
					$translate[$key] = utf8_decode('Bósnia e Herzegovina');
				break;

				case 'Bulgaria':
					$translate[$key] = utf8_decode('Bulgária');
				break;

				case 'Croatia':
					$translate[$key] = utf8_decode('Croácia');
				break;

				case 'Czech Republic':
					$translate[$key] = utf8_decode('República Checa');
				break;

				case 'Estonia':
					$translate[$key] = utf8_decode('Estônia');
				break;

				case 'Hungary':
					$translate[$key] = utf8_decode('Hungria');
				break;

				case 'Israel':
					$translate[$key] = utf8_decode('Israel');
				break;

				case 'Latvia':
					$translate[$key] = utf8_decode('Letônia');
				break;

				case 'Lithuania':
					$translate[$key] = utf8_decode('Lituânia');
				break;

				case 'FYR Macedonia':
					$translate[$key] = utf8_decode('República da Macedônia');
				break;

				case 'Moldova':
					$translate[$key] = utf8_decode('Moldávia');
				break;

				case 'Montenegro':
					$translate[$key] = utf8_decode('Montenegro');
				break;

				case 'Poland':
					$translate[$key] = utf8_decode('Polônia');
				break;

				case 'Romania':
					$translate[$key] = utf8_decode('Romania');
				break;

				case 'Russia':
					$translate[$key] = utf8_decode('Rússia');
				break;

				case 'Serbia':
					$translate[$key] = utf8_decode('Sérvia');
				break;

				case 'Slovakia':
					$translate[$key] = utf8_decode('Eslováquia');
				break;

				case 'Slovenia':
					$translate[$key] = utf8_decode('Eslovênia');
				break;

				case 'Ukraine':
					$translate[$key] = utf8_decode('Ucrânia');
				break;

				case 'South America':
					$translate[$key] = utf8_decode('América do Sul');
				break;

				case 'Argentina':
					$translate[$key] = utf8_decode('Argentina');
				break;

				case 'Bolivia':
					$translate[$key] = utf8_decode('Bolívia');
				break;

				case 'Brazil':
					$translate[$key] = utf8_decode('Brasil');
				break;

				case 'Chile':
					$translate[$key] = utf8_decode('Chile');
				break;

				case 'Colombia':
					$translate[$key] = utf8_decode('Colômbia');
				break;

				case 'Ecuador':
					$translate[$key] = utf8_decode('Equador');
				break;

				case 'Paraguay':
					$translate[$key] = utf8_decode('Paraguai');
				break;

				case 'Uruguay':
					$translate[$key] = utf8_decode('Uruguai');
				break;

				case 'Venezuela':
					$translate[$key] = utf8_decode('Venezuela');
				break;

				case 'Mexico':
					$translate[$key] = utf8_decode('México');
				break;

				case 'USA':
					$translate[$key] = utf8_decode('Estados Unidos');
				break;

				case 'Costa Rica':
					$translate[$key] = utf8_decode('Costa Rica');
				break;

				case 'El Salvador':
					$translate[$key] = utf8_decode('El Salvador');
				break;

				case 'Guatemala':
					$translate[$key] = utf8_decode('Guatemala');
				break;

				case 'Honduras':
					$translate[$key] = utf8_decode('Honduras');
				break;

				case 'China':
					$translate[$key] = utf8_decode('China');
				break;

				case 'India':
					$translate[$key] = utf8_decode('Índia');
				break;

				case 'Japan':
					$translate[$key] = utf8_decode('Japão');
				break;

				case 'Republic of Korea':
					$translate[$key] = utf8_decode('República da Coreia');
				break;

				case 'Singapore':
					$translate[$key] = utf8_decode('Singapura');
				break;

				case 'Thailand':
					$translate[$key] = utf8_decode('Tailândia');
				break;

				case 'Armenia':
					$translate[$key] = utf8_decode('Arménia');
				break;

				case 'Azerbaijan':
					$translate[$key] = utf8_decode('Azerbaijão');
				break;

				case 'Georgia':
					$translate[$key] = utf8_decode('Geórgia');
				break;

				case 'Kazakhstan':
					$translate[$key] = utf8_decode('Cazaquistão');
				break;

				case 'Iran':
					$translate[$key] = utf8_decode('Irã');
				break;

				case 'Australia':
					$translate[$key] = utf8_decode('Austrália');
				break;

				case 'New Zealand':
					$translate[$key] = utf8_decode('Nova Zelândia');
				break;

				case 'Algeria':
					$translate[$key] = utf8_decode('Argélia');
				break;

				case 'Egypt':
					$translate[$key] = utf8_decode('Egito');
				break;

				case 'Morocco':
					$translate[$key] = utf8_decode('Marrocos');
				break;

				case 'South Africa':
					$translate[$key] = utf8_decode('África do Sul');
				break;

				case 'Tunisia':
					$translate[$key] = utf8_decode('Tunísia');
				break;

				case 'Albania':
					$translate[$key] = utf8_decode('Albânia');
				break;

				case 'Faroe Islands':
					$translate[$key] = utf8_decode('Ilhas Feroe');
				break;

				case 'Malta':
					$translate[$key] = utf8_decode('Malta');
				break;

				case 'Canada':
					$translate[$key] = utf8_decode('Canadá');
				break;

				case 'Jamaica':
					$translate[$key] = utf8_decode('Jamaica');
				break;

				case 'Hong Kong':
					$translate[$key] = utf8_decode('Hong Kong');
				break;

				case 'Jordan':
					$translate[$key] = utf8_decode('Jordânia');
				break;

				case 'Malaysia':
					$translate[$key] = utf8_decode('Malásia');
				break;

				case 'Qatar':
					$translate[$key] = utf8_decode('Catar');
				break;

				case 'Saudi Arabia':
					$translate[$key] = utf8_decode('Arábia Saudita');
				break;

				case 'U.A.E.':
					$translate[$key] = utf8_decode('Emirados Árabes');
				break;

				case 'Uzbekistan':
					$translate[$key] = utf8_decode('Uzbequistão');
				break;

				case 'Uganda':
					$translate[$key] = utf8_decode('Uganda');
				break;

				default:
					$translate[$key] = utf8_decode("...");
				break;
			}

		}
		
		*/

		
		/*
		$query = "INSERT INTO tb_country (country_id, country_name) VALUES ";
		$values = [];
		$index = 0;


		for($i = 0; $i < count($result); $i++) {
			
			$query .= "(:country_id".$i.", :country_name".$i.")";

			if ( $i == (count($result)-1)  ) {
				$query .= ";";
			} else {
				$query .= ",";
			}

			$values[":country_id".$i] 	= $result[$i]->country_id;
			$values[":country_name".$i] = $result[$i]->country_name;

		}

		return $this->query( $query, $values );
		*/
		

	}
	
}

 ?>