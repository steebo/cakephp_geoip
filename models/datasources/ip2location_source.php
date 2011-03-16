<?php

$plugin = Inflector::camelize(basename(realpath(dirname(__FILE__) . '/../..')));
foreach (array('ip2location.class') as $filename) {
	App::import('Vendor', $plugin . '.cakephp_maxmind_' . str_replace('/', '_', $filename), array('file' => 'vendors/ip2location/' . $filename . '.php'));
}
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class Ip2locationSource extends GeoipCommonSource {
	
	function selectByIp($config, $ip, $ip_number) {
		if (trim(@$config['path']) == '') return array();
		if (!file_exists(@$config['path'])) return array();

		$ip2location = new ip2location;
		$ip2location->open($config['path']);
		
		$result = (array)$ip2location->getAll($ip);

		$result['ip'] = $ip;

		return $result;
	}
	
}

