<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'Model.php';

define("MAX_COLUMN_UPDATE", 25);

class Event_model extends Model {

	public function __construct() {
		parent::__construct();
	}

	public function teste() {

		echo 'Event model - teste:'.'<br>';

		$results = $this->db->select("SELECT * FROM tb_myevent WHERE intactive = 1");

		return $this->setValues($results);


		// echo 'Teste do event model';
		
	}

	// Atualiza a tabela para todos os jogos para o dia x
	public function updateAllEvent(array $updateTable, int $tableRows):bool { // OK

		if ( empty($updateTable) || !isset($updateTable) || $tableRows < 0 ) return false;

		$rowsUpdate = count($updateTable);
		$rowsNull 	= $tableRows - $rowsUpdate;

		// var_dump( $rowsUpdate, $updateTable );
		// exit;

		$start = 1;
		$index = 0;

		while ( $start <= $rowsUpdate ) {
			
			$this->query = "INSERT INTO tb_myevent (
				idmyevent, 
				idmyleague,
				idevent, 
				idleague,
				desleague,
				idhometeam, 
				idawayteam,
				deshometeam, 
				desawayteam,
				desss,
				idourevent,
				inttimestatus, 
				intactive, 
				inttime,
				dtetime, 
				dteupdate
			) VALUES ";

			$this->query .= "(
				'{$start}', 
				'{$updateTable[$index]['idmyleague']}', 
				'{$updateTable[$index]['idevent']}', 
				'{$updateTable[$index]['idleague']}', 
				'{$updateTable[$index]['desleague']}', 
				'{$updateTable[$index]['idhometeam']}', 
				'{$updateTable[$index]['idawayteam']}',
				'{$updateTable[$index]['deshometeam']}', 
				'{$updateTable[$index]['desawayteam']}', 
				'".
					(!isset($updateTable[$index]['desss']) ? 'NULL' : $updateTable[$index]['desss'])
				."', 
				'{$updateTable[$index]['idourevent']}', 
				'{$updateTable[$index]['inttimestatus']}',
				'1',
				'{$updateTable[$index]['inttime']}', 
				'{$updateTable[$index]['dtetime']}', 
				NOW()
			)";

			$start++;
			$index++;


			for ( $i = 1; $i < MAX_COLUMN_UPDATE && $start <= $rowsUpdate; $i++, $start++, $index++ ) { 
				
				$this->query .= ", (
					'{$start}', 
					'{$updateTable[$index]['idmyleague']}', 
					'{$updateTable[$index]['idevent']}', 
					'{$updateTable[$index]['idleague']}', 
					'{$updateTable[$index]['desleague']}', 
					'{$updateTable[$index]['idhometeam']}', 
					'{$updateTable[$index]['idawayteam']}',
					'{$updateTable[$index]['deshometeam']}', 
					'{$updateTable[$index]['desawayteam']}', 
					'".
						(!isset($updateTable[$index]['desss']) ? 'NULL' : $updateTable[$index]['desss'])
					."', 
					'{$updateTable[$index]['idourevent']}', 
					'{$updateTable[$index]['inttimestatus']}',
					'1',
					'{$updateTable[$index]['inttime']}', 
					'{$updateTable[$index]['dtetime']}', 
					NOW()
				)";

			}


			$this->query .= " 
				ON DUPLICATE KEY UPDATE 
				idmyevent 		= VALUES(idmyevent),
				idmyleague 		= VALUES(idmyleague),
				idevent 		= VALUES(idevent),
				idleague 		= VALUES(idleague),
				desleague 		= VALUES(desleague),
				idhometeam 		= VALUES(idhometeam),
				idawayteam 		= VALUES(idawayteam),
				deshometeam 	= VALUES(deshometeam),
				desawayteam 	= VALUES(desawayteam),
				desss 			= VALUES(desss),
				idourevent 		= VALUES(idourevent),
				inttimestatus 	= VALUES(inttimestatus),
				intactive 		= VALUES(intactive),
				inttime 		= VALUES(inttime),
				dtetime 		= VALUES(dtetime),
				dteupdate 		= VALUES(dteupdate)
			";


			// var_dump( $this->query );
			// exit;


			$this->db->select($this->query);

		}
		
		// ----------------------------------------------------------------
		
		if ( $rowsNull > 0 ) {

			$result = $this->db->select("
				UPDATE 	tb_myevent
				SET 	intactive = 0
				WHERE 	idmyevent BETWEEN :IDSTART AND :IDFINAL
			", [
				':IDSTART' => $rowsUpdate + 1,
				':IDFINAL' => $rowsUpdate + $rowsNull
			]);

		}
		
		// ----------------------------------------------------------------

		/*
		echo 'Update  Rows:'.'<br>';

		echo 'Table Rows: '.$tableRows.'<br>';
		echo 'Update Rows: '.$rowsUpdate.'<br>';
		echo 'Null Rows: '.$rowsNull.'<br>';
		*/

		return true;

	}

	// Atualiza todos os jogos que estão num intervalo de 2h a patir do momento do update e que estão ativos
	// ou seja disponiveis para aposta
	public function updateEvent(array $updateTable):bool { // OK

		if ( empty($updateTable) || !isset($updateTable) ) return false;

		// echo 'Update Coming:'.'<br>';

		/*
		$idevent = [];

		foreach ($updateTable as $value) {
			$idevent[] = (int) $value['idevent'];
		}

		var_dump( $idevent );

		exit;
		*/

		// var_dump( $updateTable );

		$this->query = "
			UPDATE tb_myevent A 
			INNER JOIN ( 
		";

		// $updateTable[0]['intss'] = 1;

		$this->query .= "
			SELECT 
			'{$updateTable[0]['idmyevent']}'		idmyevent, 
			'{$updateTable[0]['idmyleague']}'		idmyleague,
			'{$updateTable[0]['idevent']}'			idevent, 
			'{$updateTable[0]['idleague']}'			idleague,
			'{$updateTable[0]['desleague']}'		desleague,
			'{$updateTable[0]['idhometeam']}'		idhometeam, 
			'{$updateTable[0]['idawayteam']}'		idawayteam,
			'{$updateTable[0]['deshometeam']}'		deshometeam, 
			'{$updateTable[0]['desawayteam']}'		desawayteam,
			'".
				(!isset($updateTable[$index]['desss']) ? 'NULL' : $updateTable[$index]['desss'])."'	desss,
			'{$updateTable[0]['idourevent']}'		idourevent,
			'{$updateTable[0]['inttimestatus']}'	inttimestatus,
			'{$updateTable[0]['inttime']}'			inttime,
			'{$updateTable[0]['dtetime']}'			dtetime, 
			'{$updateTable[0]['dteupdate']}'		dteupdate
		";

		
		for ($i = 1; $i < count($updateTable); $i++) { 

			$this->query .= "
			UNION SELECT 
			'{$updateTable[$i]['idmyevent']}', 
			'{$updateTable[$i]['idmyleague']}',
			'{$updateTable[$i]['idevent']}', 
			'{$updateTable[$i]['idleague']}',
			'{$updateTable[$i]['desleague']}',
			'{$updateTable[$i]['idhometeam']}', 
			'{$updateTable[$i]['idawayteam']}',
			'{$updateTable[$i]['deshometeam']}', 
			'{$updateTable[$i]['desawayteam']}',
			'".
				(!isset($updateTable[$i]['desss']) ? 'NULL' : $updateTable[$i]['desss'])."',
			'{$updateTable[$i]['idourevent']}',
			'{$updateTable[$i]['inttimestatus']}',
			'{$updateTable[$i]['inttime']}',
			'{$updateTable[$i]['dtetime']}', 
			'{$updateTable[$i]['dteupdate']}'
		";
		}
		

		$this->query .= "
			) B USING (idmyevent)
			SET 
			A.idmyleague 		= B.idmyleague, 
			A.idevent 			= B.idevent, 
			A.idleague 			= B.idleague,
			A.desleague 		= B.desleague,
			A.idhometeam 		= B.idhometeam, 
			A.idawayteam 		= B.idawayteam,
			A.deshometeam 		= B.deshometeam, 
			A.desawayteam 		= B.desawayteam,
			A.desss 			= B.desss,
			A.idourevent 		= B.idourevent,
			A.inttimestatus 	= B.inttimestatus,
			A.inttime 			= B.inttime,
			A.dtetime 			= B.dtetime, 
			A.dteupdate 		= B.dteupdate
			WHERE 
			A.intactive 		= 1;;
		";

		// var_dump( $this->query );

		$result = $this->db->select($this->query);

		// var_dump( $result );

		/*
			update tb_myevent A inner join
			(
			    SELECT 63615848 idevent, 0 intactive UNION
			    SELECT 63613497, 0

			) B USING (idevent)
			SET A.intactive = B.intactive;


			update tb_myevent A inner join
			(
			    SELECT 63615848 idevent UNION
			    SELECT 63613497

			) B USING (idevent)
			SET A.dteupdate = NOW();

		*/

		return true;

	}

	public function setEvent(array $allowed):bool { // OK


		if ( empty($allowed) || !isset($allowed) ) return false;

		$numberRow = $this->getNumberRows('tb_myevent');

		if ( !isset($numberRow) ) return false;

		return $this->updateAllEvent(
			$allowed,
			$numberRow
		);

	}

	public function setEventByDate(string $start, string $final):bool { // OK

		if ( empty($start) || empty($final) ) return false;

		$fields = $this->getField();

		$this->query = "
			SELECT {$fields}
			FROM tb_myevent
			WHERE (dtetime BETWEEN :START AND :FINAL) AND intactive = 1
		";

		$result = $this->db->select($this->query, [
			':START'	=> $start,
			':FINAL'	=> $final
		]);

		return $this->setValues($result);

	}

}

 ?>