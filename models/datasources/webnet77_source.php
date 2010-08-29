<?php

class Webnet77Source extends DataSource {
	
	function __construct($config) {
		$this->_path = realpath($config['path']);
	}
	
	function describe($model) {
	}
	
	function listSources() {
	}
	
	function create($model, $fields = array(), $values = array()) {
	}
	
	function _currentIp() { 
		switch (true) {
			case !empty($_SERVER['HTTP_CLIENT_IP']): return $_SERVER['HTTP_CLIENT_IP'];
			case !empty($_SERVER['HTTP_X_FORWARDED_FOR']): return $_SERVER['HTTP_X_FORWARDED_FOR'];
			default: return $_SERVER['REMOTE_ADDR'];
		}
	} 
	
	function _convert($ip) {
		list($a, $b, $c, $d) = explode('.', $ip, 4);
		return 16777216 * $a + 65536 * $b + 256 * $c + $d;
	}
	
	function read($model, $queryData = array()) {
 		$ip = @$queryData['conditions']['ip'];
		if (empty($ip)) $ip = $this->_currentIp();
		$ip_number = $this->_convert($ip);
		
		$result = a();
		if ($fp = fopen($this->_path, 'r')) {
			while (($csv = fgetcsv($fp, 8192)) !== false) {
				if (substr($csv[0], 0, 1) == '#') continue;
				list($start, $end, $registry, $assigned, $country_code, $country_code3, $country_name) = $csv;
				if ($ip_number < $start) continue;
				if ($ip_number > $end) continue;
				$result = compact('ip', 'registry', 'assigned', 'country_code', 'country_code3', 'country_name');
				ksort($result);
				break;
			}
			fclose($fp);
		}

		return a(aa($model->name, $result));
	}
	
	function update($model, $fields = array(), $values = array()) {
	}
	
	function delete($model, $id = null) {
	}
	
}

?>