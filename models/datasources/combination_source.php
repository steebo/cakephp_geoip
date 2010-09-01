<?php

$plugin = Inflector::camelize(basename(realpath(__FILE__ . '/../../..')));
App::import('DataSource', $plugin . '.GeoipCommonSource');
unset($plugin);

class CombinationSource extends GeoipCommonSource {
	
	function read($model, $queryData = array()) {
		$result = $this->_createGeoipRecord();
		$plugin = Inflector::camelize(basename(realpath(__FILE__ . '/../../..')));
		
		foreach (array_reverse($this->_priority) as $source => $path) {
			$clz = Inflector::camelize($source) . 'Source';
			App::import('DataSource', $plugin . '.' . $clz);
			$source = new $clz(a());
			$source->_path = $path;
			$r = array_shift($source->read($model, $queryData));
			
			foreach ($r[$model->name] as $key => $value) {
				if (!empty($value)) $result[$key] = $value;
			}
		}

		return a(aa($model->name, $result));
	}
	
}

?>