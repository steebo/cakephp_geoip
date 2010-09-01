<?php

class GeoipCommonSource extends DataSource {
	
	var $_geoipSchema = array(
		'area_code',
		'city',
		'city',
		'continent_code',
		'country_code',
		'country_code3',
		'country_name',
		'dma_code',
		'ip',
		'latitude',
		'longitude',
		'metro_code',
		'organization',
		'postal_code',
		'region',
		'registry',
		'state',
		'tech_contact',
	);
	
	function __construct($config) {
		foreach ($config as $field => $value) {
			$this->{'_' . $field} = $config[$field];
		}
	}
	
	function _createGeoipRecord() {
		$record = a();
		foreach ($this->_geoipSchema as $field) $record[$field] = false;
		return $record;
	}
	
	function describe($model) {
	}
	
	function listSources() {
		return a('geoips');
	}
	
	function create($model, $fields = array(), $values = array()) {
	}
	
	function _extractIp($model, $queryData) {
 		$ip = false;
		foreach ((array)@$queryData['conditions'] as $field => $value) {
			if (empty($value)) continue;
			list($key, $field) = pluginSplit($field);
			switch (true) {
				case ($key == $model->name) && (low($field) == 'ip'):
				case ($key == '') && (low($field) == 'ip'):
					$ip = $value;
			}
			if ($ip) break;
		}
		if (empty($ip)) $ip = $this->_currentIp();
		return $ip;
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
	}
	
	function update($model, $fields = array(), $values = array()) {
	}
	
	function delete($model, $id = null) {
	}

}

?>