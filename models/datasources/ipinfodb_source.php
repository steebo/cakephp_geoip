<?php

$plugin = Inflector::camelize(basename(realpath(dirname(__FILE__) . '/../..')));
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class IpinfodbSource extends GeoipCommonSource {
	
	var $endpoint = 'http://ipinfodb.com/ip_query.php?ip=%s&output=json&timezone=true';
	
	function _transkey(&$result, $old_key, $new_key) {
		$result[$new_key] = $result[$old_key];
		unset($result[$old_key]);
	}
	
	function selectByIp($config, $ip, $ip_number) {
		$result = a();
		foreach (json_decode(file_get_contents(sprintf($this->endpoint, $ip)), true) as $key => $value) {
			$result[low($key)] = $value;
		}
		$this->_transkey($result, 'countrycode', 'country_code');
		$this->_transkey($result, 'countryname', 'country_name');
		$this->_transkey($result, 'regioncode', 'region');
		$this->_transkey($result, 'regionname', 'region_name');
		$this->_transkey($result, 'zippostalcode', 'postal_code');
		$this->_transkey($result, 'timezonename', 'timezone');
		$this->_transkey($result, 'gmtoffset', 'gmt_offset');
		$this->_transkey($result, 'isdst', 'is_dst');
		return $result;
	}
	
}

?>