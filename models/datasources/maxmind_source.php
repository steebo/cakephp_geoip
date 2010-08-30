<?php

$plugin = Inflector::camelize(basename(realpath(__FILE__ . '/../../..')));
foreach (a('geoip', 'geoipregionvars', 'geoipcity') as $filename) {
	App::import('Vendor', $plugin . '.cakephp_maxmind_' . r('/', '_', $filename), aa('file', 'vendors/maxmind/' . $filename . '.php'));
}
unset($plugin);

class MaxmindSource extends DataSource {
	
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
	
	function read($model, $queryData = array()) {
 		$ip = @$queryData['conditions']['ip'];
		if (empty($ip)) $ip = $this->_currentIp();
		
		$gi = geoip_open($this->_path, GEOIP_STANDARD); 
		if ($gi->databaseType == GEOIP_CITY_EDITION_REV1) {
			$result = (array)geoip_record_by_addr($gi, $ip);
		} else {
			$result = a();
			$result['country_code'] = geoip_country_code_by_addr($gi, $ip);
			$result['country_name'] = geoip_country_name_by_addr($gi, $ip);
		}
		$result['ip'] = $ip;
		ksort($result);
        geoip_close($gi);

		return a(aa($model->name, $result));
	}
	
	function update($model, $fields = array(), $values = array()) {
	}
	
	function delete($model, $id = null) {
	}
	
}

?>