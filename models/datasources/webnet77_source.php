<?php

$plugin = Inflector::camelize(basename(realpath(__FILE__ . '/../../..')));
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class Webnet77Source extends GeoipCommonSource {
	
	function read($model, $queryData = array()) {
		$ip = $this->_extractIp($model, $queryData);
		$ip_number = $this->_convert($ip);
		
		$result = a();
		if ($fp = fopen($this->_path, 'r')) {
			while (($csv = fgetcsv($fp, 8192)) !== false) {
				if (substr($csv[0], 0, 1) == '#') continue;
				list($start, $end, $registry, $assigned, $country_code, $country_code3, $country_name) = $csv;
				if ($ip_number < $start) continue;
				if ($ip_number > $end) continue;
				$result = am($this->_createGeoipRecord(), compact('ip', 'registry', 'country_code', 'country_code3', 'country_name'));
				ksort($result);
				break;
			}
			fclose($fp);
		}

		return a(aa($model->name, $result));
	}
	
}

?>