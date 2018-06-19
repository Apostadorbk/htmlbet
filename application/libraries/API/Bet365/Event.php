<?php 
/*
	Está classe fará toda logica que usa ligas e com os arquivos cache
*/

/*
	Upcoming Events
	sport_id	Yes	R-SportID
	league_id	No	useful when you want only one league
	day			No	format YYYYMMDD, eg: 20161201
	page		No	R-Pager
*/
	
class Event {

	private $sportID;
	private $leagueID;
	private $dayStart;
	private $dayEnd;

	private $page;
	private $perPage;
	private $totalPages;

	public function __construct() {
		$this->setSportID();
	}

	public function setSportID (string $sportID = '1') {
		$this->sportID = (!empty($sportID)) ? $sportID : NULL;
	}

	public function setleagueID (string $leagueID = '') {
		$this->leagueID = (!empty($leagueID)) ? $leagueID : NULL;
	}

	public function setDay (int $dayStart, int $dayEnd) {
		$this->dayStart = ($dayStart > 0) 	? $dayStart : NULL;
		$this->dayEnd	= ($dayEnd > 0)		? $dayEnd	: NULL;
	}

	public function setPager (int $page = 0, int $perPage = 0, int $totalPages = 0) {
		$this->page 		= ($page > 0) 		? $page 		: NULL;
		$this->perPage 		= ($perPage > 0) 	? $perPage 		: NULL; 
		$this->totalPages	= ($totalPages > 0) ? $totalPages 	: NULL;
	}
	
	public function teste($event) {

		var_dump( $event );
		
	}

}

?>