<?php 

/*
interface iCachePage {
	public function setBaseDirectory(string $dir);
	public static function get();
}
*/

/*
	Estruturação do CachePage

*/

// define("BASEVIEW", APPPATH.'views/');

// define("CACHEDIR", VIEWDIR."cache_page/");

define("VIEWDIR", APPPATH.'views');

class Page {

	private $cache;
	private $cacheName;
	private $cacheFile;
	private $time;

	public function __construct(string $filename, int $time = 0) { // OK

		$this->cacheFile = VIEWDIR.DS;

		$this->createDir("cache_page/".$filename.".html");

		$this->time = ($time >= 0) ? $time : 0;

	}

	public function setCache(string $value):bool { // OK

		$this->cache = $value;

		return $this->saveCache();

	}

	public function getCache() { // OK
		return $this->readCache() ? $this->cache : false;
	}
	
	public function isValid():bool { // OK

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

		if ( !file_exists($this->cacheFile) ) return false;

		$read = file_get_contents($this->cacheFile);
		
		// Caso dê falhan a leitura
		if ( !$read ) return false;

		$this->cache = $read;

		return true;

	}

	private function saveCache():bool { // OK
		return (bool) file_put_contents($this->cacheFile, $this->cache);
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

}

?>