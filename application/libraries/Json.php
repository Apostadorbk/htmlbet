<?php 

define("CACHEJSONDIR", APPPATH."cache");

class Json {

	protected $cache;
	protected $cacheFile;
	protected $time;

	public function __construct(string $filename, int $time = 0) {

		$this->cacheFile = CACHEJSONDIR.DS;

		$this->createDir("cache_json/".$filename.".json");

		$this->setTime($time);

		$this->cache = [];

	}

	/*
	public function setCache($key, $value) {
		$this->cache[$key] = $value;
	}

	public function getCache() {
		return $this->cache;
	}
	*/

	public function hasValue($key):bool {
		return isset($this->cache[$key]);
	}

	public function sortKey(string $flag = 'STRING'):bool {

		if ( !isset($this->cache) || empty($this->cache) ) return false;

		switch ( $flag ) {

			case 'STRING':
				return ksort($this->cache, SORT_STRING);
			break;

			case 'REGULAR':
				return ksort($this->cache, SORT_REGULAR);
			break;

			case 'NUMERIC':
				return ksort($this->cache, SORT_NUMERIC);
			break;

		}

	}

	public function setTime(int $time) {
		$this->time = ($time >= 0) ? $time : 0;
	}

	// ---------------------------------------------------------------------

	public function setVar($key, $value):bool { // OK
		$this->readCache();
		$this->cache[$key] = $value;
		return $this->saveCache();
	}

	public function setVars(array $array = []):bool { // OK

		if ( empty($array) || !isset($array) ) return false;

		$this->readCache();

		foreach ($array as $key => $value) {
			$this->cache[$key] = $value;
		}
		
		return $this->saveCache();
	}

	public function getVar($key) { // OK
		if ( !$this->readCache() ) return false;

		return $this->cache[$key] ?? false;
	}

	public function getVars(array $keys = []) { // OK
		
		if ( !$this->readCache() ) return false;

		if ( empty($keys) ) return $this->cache;

		$aux = [];

		foreach ($keys as $value) {
			if ( isset($this->cache[$value]) ) {
				$aux[$value] = $this->cache[$value];
			} else {
				$aux[$value] = NULL;
			}
		}

		return $aux;
	}

	// ---------------------------------------------------------------------
	
	// Opcional
	public function isValid() { // OK

		if ( !file_exists($this->cacheFile) ) return false;

		if ( $this->time == 0 ) return true;

		$last_modification = filemtime($this->cacheFile);
		$interval = time() - $last_modification;

		if ( $interval > $this->time ) {
			return false;
		} else {
			return true;
		}

	}


	private function readCache():bool { // OK

		$this->cache = [];

		if ( !file_exists($this->cacheFile) ) return false;

		$read = file_get_contents($this->cacheFile);

		// Caso o arquivo seja novo e não tem nada escrito nele
		if ( empty($read) ) return true;

		// Caso dê falhan a leitura
		if ( $read == false ) return false;


		$this->cache = json_decode($read, true);

		return true;
		
	}


	private function saveCache():bool { // OK
		return (bool) file_put_contents($this->cacheFile, json_encode($this->cache));
	}

	private function createDir(string $filename) { // OK
		
		$array = explode("/", $filename);
		$index = 0;
			
		while ( $index < (count($array)-1) ) {
			
			$this->cacheFile .= $array[$index].DS;

			if ( !file_exists($this->cacheFile) ) mkdir($this->cacheFile);

			$index++;
		
		}
			
		$this->cacheFile .= $array[$index];

	}

	public function saveJson(string $json = 'NULL'):bool { // OK
		return (bool) file_put_contents($this->cacheFile, $json);
	}

	public function readJson() { // OK

		$read = file_get_contents($this->cacheFile);
		
		// Caso dê falhan a leitura
		if ( !$read ) return false;

		return $read;
	
	}

	public function hasFile():bool { // OK
		return file_exists($this->cacheFile);
	}

	public function isEmpty():bool { // OK
		
		if ( !$this->hasFile() ) return false;

		$data = $this->readJson();

		return empty($data);

	}

	// -------------------------------------------------------------------------
	// TEMPO

	protected function getDate(string $time = 'now', string $format = 'Y-m-d H:i:s'):string {

		if ( $time == 'now' ) 
			return Time::currentDate();
		else 
			return date( $format, strtotime($time, Time::currentTime()) );

	}

}


?>