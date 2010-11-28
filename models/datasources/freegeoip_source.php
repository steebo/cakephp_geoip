<?php

$plugin = Inflector::camelize(basename(realpath(dirname(__FILE__) . '/../..')));
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class FreegeoipSource extends GeoipCommonSource {
	
	var $endpoint = 'http://freegeoip.appspot.com/json/%s';
	
	function _transkey(&$result, $old_key, $new_key) {
		$result[$new_key] = $result[$old_key];
		unset($result[$old_key]);
	}
	
	function selectByIp($config, $ip, $ip_number) {
		$result = json_decode(file_get_contents(sprintf($this->endpoint, $ip)), true);
		$this->_transkey($result, 'countrycode', 'country_code');
		$this->_transkey($result, 'countryname', 'country_name');
		$this->_transkey($result, 'regioncode', 'region');
		$this->_transkey($result, 'regionname', 'region_name');
		$this->_transkey($result, 'zipcode', 'postal_code');
		return $result;
	}
	
}

