<?php

$plugin = Inflector::camelize(basename(realpath(__FILE__ . '/../../..')));
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class WorldipApiSource extends GeoipCommonSource {
	
	var $endpoint = 'http://api.wipmania.com/%s?%s';
	
	function read($model, $queryData = array()) {
		$ip = $this->_extractIp($model, $queryData);
		
		$country_code = trim(file_get_contents(sprintf($this->endpoint, $ip, $_SERVER['HTTP_HOST'])));
		$result = am($this->_createGeoipRecord(), compact('ip', 'country_code'));

		return a(aa($model->name, $result));
	}
	
}

?>