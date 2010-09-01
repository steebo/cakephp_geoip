<?php

class GeoipCommonSource extends DataSource {
	
	var $_geoipSchema = array(
		'city',
		'country_code',
		'ip',
		'organization',
		'state',
		'tech_contact',
		'country_code3',
		'country_name',
		'registry',
		'area_code',
		'city',
		'continent_code',
		'dma_code',
		'latitude',
		'longitude',
		'metro_code',
		'postal_code',
		'region',
	);
	
	function __construct($config) {
		$this->_path = realpath($config['path']);
	}
	
	function _createGeoipRecord() {
		$record = a();
		foreach ($this->_geoipSchema as $field) $record[$field] = false;
		return $record;
	}
	
	function describe($model) {
	}
	
	function listSources() {
		return a('geoips', 'countries');
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
		switch ($model->table) {
			case 'geoips': return $this->_readGeoip($model, $queryData);
			case 'countries': return $this->_readCountry($model, $queryData);
		}
	}
	
	function _readGeoip($model, $queryData) {
	}
	
	function _readCountry($model, $queryData) {
		return a('test');
	}
	
	function update($model, $fields = array(), $values = array()) {
	}
	
	function delete($model, $id = null) {
	}

}

?>