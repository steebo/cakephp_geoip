<?php

$plugin = Inflector::camelize(basename(realpath(__FILE__ . '/../../..')));
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class LinuxboxSource extends GeoipCommonSource {
	
	function read($model, $queryData = array()) {
		$ip = $this->_extractIp($model, $queryData);
		$ip_number = $this->_convert($ip);
		
		$result = a();
		if ($fp = fopen($this->_path, 'r')) {
			while (($csv = fgetcsv($fp, 8192, ':')) !== false) {
				list($start, $end, , , $organization, $city, $state, $country_code, $tech_contact) = $csv;
				if ($ip_number < $start) continue;
				if ($ip_number > $end) continue;
				$result = am($this->_createGeoipRecord(), compact('ip', 'country_code', 'city', 'state', 'tech_contact', 'organization'));
				ksort($result);
				break;
			}
			fclose($fp);
		}

		return a(aa($model->name, $result));
	}
	
}

?>