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

		$results = $this->db->select("SELECT * FROM tb_upcomingevent");

		$idevent = [];

		foreach ($results as $value) {
			$idevent[] = (int) $value['idevent'];
		}

		var_dump( array_unique($idevent) );

		// echo 'Teste do event model';
		
	}

	// Atualiza a tabela para todos os jogos para o dia x
	public function updateAllUpcoming(array $updateTable, int $tableRows):bool { // OK

		if ( empty($updateTable) || !isset($updateTable) || $tableRows < 0 ) return false;

		$rowsUpdate = count($updateTable);
		$rowsNull 	= $tableRows - $rowsUpdate;

		// var_dump( $rowsUpdate, $updateTable );
		// exit;

		$start = 1;
		$index = 0;

		while ( $start <= $rowsUpdate ) {
			
			$query = "INSERT INTO tb_upcomingevent (
				idupcomingevent, 
				idleague, 
				idevent, 
				idhometeam, 
				idawayteam,
				deshometeam, 
				desawayteam, 
				dtetime, 
				inttimestatus, 
				dteupdate, 
				intactive, 
				intss
			) VALUES ";

			$query .= "(
				'{$start}', 
				'{$updateTable[$index]['idleague']}', 
				'{$updateTable[$index]['idevent']}', 
				'{$updateTable[$index]['idhometeam']}', 
				'{$updateTable[$index]['idawayteam']}',
				'{$updateTable[$index]['deshometeam']}', 
				'{$updateTable[$index]['desawayteam']}', 
				'{$updateTable[$index]['dtetime']}', 
				'{$updateTable[$index]['inttimestatus']}', 
				NOW(), 
				'1', 
				".
				(!isset($updateTable[$index]['intss']) ? 'NULL' : $updateTable[$index]['intss'])
				."
			)";

			$start++;
			$index++;


			for ( $i = 1; $i < MAX_COLUMN_UPDATE && $start <= $rowsUpdate; $i++, $start++, $index++ ) { 
				
				$query .= ", (
					'{$start}', 
					'{$updateTable[$index]['idleague']}', 
					'{$updateTable[$index]['idevent']}', 
					'{$updateTable[$index]['idhometeam']}', 
					'{$updateTable[$index]['idawayteam']}',
					'{$updateTable[$index]['deshometeam']}', 
					'{$updateTable[$index]['desawayteam']}', 
					'{$updateTable[$index]['dtetime']}', 
					'{$updateTable[$index]['inttimestatus']}', 
					NOW(), 
					'1', 
					".
					(!isset($updateTable[$index]['intss']) ? 'NULL' : $updateTable[$index]['intss'])
					."
				)";

			}


			$query .= " 
				ON DUPLICATE KEY UPDATE 
				idleague		= VALUES(idleague), 
				idevent			= VALUES(idevent), 
				idhometeam		= VALUES(idhometeam), 
				idawayteam		= VALUES(idawayteam), 
				deshometeam		= VALUES(deshometeam), 
				desawayteam		= VALUES(desawayteam), 
				dtetime			= VALUES(dtetime),
				inttimestatus	= VALUES(inttimestatus),
				dteupdate		= VALUES(dteupdate),
				intactive		= VALUES(intactive),
				intss			= VALUES(intss)
			";


			// var_dump( $query );
			// exit;


			$this->db->select($query);

		}
		
		// ----------------------------------------------------------------
		
		if ( $rowsNull > 0 ) {

			$result = $this->db->select("
				UPDATE tb_upcomingevent
				SET intactive = 0
				WHERE idupcomingevent BETWEEN :IDSTART AND :IDFINAL
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
	public function updateUpcoming(array $updateTable):bool { // OK

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

		$query = "
			UPDATE tb_upcomingevent A 
			INNER JOIN ( 
		";

		// $updateTable[0]['intss'] = 1;

		$query .= "
			SELECT 
			'{$updateTable[0]['idevent']}' 			idevent,
			'{$updateTable[0]['dtetime']}' 			dtetime,
			'{$updateTable[0]['inttimestatus']}' 	inttimestatus,
			'{$updateTable[0]['idleague']}' 		idleague,
			'{$updateTable[0]['desleague']}' 		desleague,
			'{$updateTable[0]['idhometeam']}' 		idhometeam,
			'{$updateTable[0]['deshometeam']}' 		deshometeam,
			'{$updateTable[0]['idawayteam']}' 		idawayteam,
			'{$updateTable[0]['desawayteam']}' 		desawayteam,
		".
			(!isset($updateTable[0]['intss']) ? 'NULL' : $updateTable[0]['intss'])." intss
		";

		
		for ($i = 1; $i < count($updateTable); $i++) { 

			// $updateTable[$i]['intss'] = 1;

			$query .= "
			UNION SELECT 
			'{$updateTable[$i]['idevent']}',
			'{$updateTable[$i]['dtetime']}',
			'{$updateTable[$i]['inttimestatus']}',
			'{$updateTable[$i]['idleague']}',
			'{$updateTable[$i]['desleague']}',
			'{$updateTable[$i]['idhometeam']}',
			'{$updateTable[$i]['deshometeam']}',
			'{$updateTable[$i]['idawayteam']}',
			'{$updateTable[$i]['desawayteam']}',
		".
			(!isset($updateTable[$i]['intss']) ? 'NULL' : $updateTable[$i]['intss'])." intss
		";
		}
		

		$query .= "
			) B USING (idevent)
			SET 
			A.idleague 		= B.idleague,
			A.idevent 		= B.idevent,
			A.idhometeam 	= B.idhometeam,
			A.idawayteam 	= B.idawayteam,
			A.deshometeam 	= B.deshometeam,
			A.desawayteam 	= B.desawayteam,
			A.dtetime 		= B.dtetime,
			A.inttimestatus = B.inttimestatus,
			A.intss 		= B.intss,
			A.dteupdate 	= NOW()
			WHERE 
			A.intactive 	= 1;
		";

		// var_dump( $query );

		$result = $this->db->select($query);

		// var_dump( $result );

		/*
			update tb_upcomingevent A inner join
			(
			    SELECT 63615848 idevent, 0 intactive UNION
			    SELECT 63613497, 0

			) B USING (idevent)
			SET A.intactive = B.intactive;


			update tb_upcomingevent A inner join
			(
			    SELECT 63615848 idevent UNION
			    SELECT 63613497

			) B USING (idevent)
			SET A.dteupdate = NOW();

		*/

		return true;

	}

	public function setUpcoming(array $allowed):bool { // OK

		if ( empty($allowed) || !isset($allowed) ) return false;

		$numberRow = $this->getNumberRows('tb_upcomingevent');

		if ( !isset($numberRow) ) return false;

		$numberAllowed = count($allowed);
		$newRows 	= 0;
		$updateRows = 0;
		$diffRows 	= 0;

		// Simulação
		// $numberRow = 10;

		switch ($numberRow <=> $numberAllowed) {
			
			case 1:

				$updateRows = $numberAllowed;
				$newRows 	= 0;

				// echo 'Number Allowed < Number Rows'.'<br>';

			break;

			case 0:

				$updateRows = $numberRow;
				$newRows 	= 0;

				// echo 'Number Allowed == Number Rows'.'<br>';

			break;

			case -1:

				$updateRows = $numberRow;
				$newRows 	= $numberAllowed - $numberRow;

				// echo 'Number Allowed > Number Rows'.'<br>';

			break;

		}

		/*
		echo '<hr>';

		echo 'Number Allowed: '.$numberAllowed.'<br>';
		echo 'Number Rows: '.$numberRow.'<br>';
		echo 'Update Rows: '.$updateRows.'<br>';
		echo 'New Rows: '.$newRows.'<br>';

		echo '<hr>';
		*/

		$this->updateAllUpcoming(
			$allowed,
			$numberRow
		);
		
		/*
		if ( $updateRows > 0 ) {
			
		}
		*/

		// echo '<hr>';

		return true;

	}

}

 ?>