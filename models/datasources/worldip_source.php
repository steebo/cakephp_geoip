<?php

$plugin = Inflector::camelize(basename(realpath(__FILE__ . '/../../..')));
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class WorldipSource extends GeoipCommonSource {
	
	function _readGeoip($model, $queryData = array()) {
 		$ip = @$queryData['conditions']['ip'];
		if (empty($ip)) $ip = $this->_currentIp();
		$ip_number = $this->_convert($ip);
		
		$result = a();
		if ($fp = fopen($this->_path, 'r')) {
			while (($csv = fgetcsv($fp, 8192)) !== false) {
				list(, , $start, $end, $country_code, $country_name) = $csv;
				if ($ip_number < $start) continue;
				if ($ip_number > $end) continue;
				$result = am($this->_createGeoipRecord(), compact('ip', 'country_code', 'country_name'));
				ksort($result);
				break;
			}
			fclose($fp);
		}

		return a(aa($model->name, $result));
	}
	
}

?>