# Supported Geoip providers

1. Maxmind (both GeoCountry and GeoCity database) - I am using the official API to interface with their binary database. So you should be able to either the commercial version or the free version - Download the database from <http://www.maxmind.com/app/geolitecity> or <http://www.maxmind.com/app/geolitecountry>
1. Worldip (WIPmania) - Download the database from <http://www.wipmania.com/en/base/>
1.  Worldip (WIPmania) API - Worldip also provides an API for your to query their database live. I have included a live API version of the data source as well. You don't have to download anything, but the catch is you have got a quota of 10,000 request per day.
1. Webnet77 - This hosting company offers their own version of geoip database as well. I am not sure where they get their data from, but you can use their database at <http://software77.net/geo-ip/>
1. LinuxBox UK - They have a geoip database based on whois records, you can downdload their database at <http://linuxbox.co.uk/ip-address-whois-database.php>
1. Free GeoIP - This is a live data source, accessed through an API, you don't have to download anything, but feel free to checkout their website <http://freegeoip.appspot.com/>. I am not sure where they get their data from, but it looks like Maxmind data - but needs to be confirmed.
1. IP Info DB - This is another live data source I can offer. you don't have to download anything, but feel free to checkout their website <http://ipinfodb.com/ip_location_api.php>. They claimed their data is from Maxmind. So if you like the Maxmind data, but don't want to keep downloading database from Maxmind website to keep up-to-date, you may want to try this data source.

# How to Use?

This geoip data source collection plugin is under my github account and you can download the codes from <http://github.com/dereklio/cakephp_geoip>.

Once downloaded, put all the source codes under your /app/plugins folder. Recommended path is /app/plugins/geoip, but any other path should be fine.

Then you need to create your own Geoip model. A very minimum model is required. Put the following codes into /app/models/geoip.php

Model Class:

	<?php  
	class Geoip extends AppModel { 
		 
		var $useDbConfig = 'geoip'; 
		 
	} 

And now, you can set up your database.php config to use one of the supported geoip providers. Refer below for the sample database config.


	<?php 

	class DATABASE_CONFIG { 
		var $default; 
		 
		var $geoip = array( 
			'datasource' => 'Geoip.webnet77', 
			'path' => '/full/path/to/the/database/file/IpToCountry.csv', 
			'cache' => '+6 months', 
		); 

		var $geoip = array( 
			'datasource' => 'Geoip.worldip', 
			'path' => '/full/path/to/the/database/file/worldip.en.txt', 
			'cache' => '+6 months', 
		); 

		var $geoip = array( 
			'datasource' => 'Geoip.worldip_api', 
			'cache' => '+6 months', 
		); 

		var $geoip = array( 
			'datasource' => 'Geoip.linuxbox', 
			'path' => '/full/path/to/the/database/file/ipv4addresses_august2009.csv', 
			'cache' => '+6 months', 
		); 

		var $geoip = array( 
			'datasource' => 'Geoip.maxmind', 
			'path' => '/full/path/to/the/database/file/GeoIP.dat', 
			'cache' => '+6 months', 
		); 

		var $geoip = array( 
			'datasource' => 'Geoip.maxmind', 
			'path' => '/full/path/to/the/database/file/GeoLiteCity.dat', 
			'cache' => '+6 months', 
		); 

		var $geoip = array( 
			'datasource' => 'Geoip.freegeoip', 
			'cache' => '+6 months', 
		); 

		var $geoip = array( 
			'datasource' => 'Geoip.ipinfodb', 
			'cache' => '+6 months', 
		); 
		 
	} 


I listed all available options in the above files, but in reality, you only need to have either one of them!!

Please also note that cache config property. It specifies how long should the geoip lookup result being cached in the cakephp tmp directory. This option is especially useful when you're working with a live data source, in order to reduce the network load and quota usage. The default cache period is 6 months.

You can also combine different data source to get a bigger picture of the geoip data. You can do this by using the combination data source.


	<?php 

	class DATABASE_CONFIG { 
		var $default; 
		 
		var $geoip = array( 
			'datasource' => 'Geoip.combination', 
			'priority' => array( 
				'ipinfodb' => array(), 
				'freegeoip' => array(), 
				'maxmind' => array( 
					'path' => '/full/path/to/the/database/file/GeoLiteCity.dat', 
				), 
				'worldip' => array( 
					'path' => '/full/path/to/the/database/file/worldip.en.txt', 
				), 
				'webnet77' => array( 
					'path' => '/full/path/to/the/database/file/IpToCountry.csv', 
				), 
				'linuxbox' => array( 
					'path' => '/full/path/to/the/database/file/ipv4addresses_august2009.csv', 
				), 
			), 
			'cache' => '+6 months', 
		); 
         

Again, all lookup result is cached!!

The actual geoip lookup codes will look like this

Controller Class:

	<?php  
	class TestController extends AppController { 
		 
		var $uses = array('Geoip'); 
		 
		function test() { 
			pr($this->Geoip->find('first')); 
			pr($this->Geoip->find('first', aa('conditions', aa('ip', '74.125.45.100')))); 
			pr($this->Geoip->find('first', aa('conditions', aa('Geoip.ip', '74.125.45.100')))); 
		} 
		 
	} 

Either one of them is ok. and the output will look like the following (please note that the following is produced using the combination data source, NOT all data source will give you all data fields.


	Array 
	( 
		[Geoip] => Array 
			( 
				[area_code] => 650 
				[city] => Mountain View 
				[continent_code] => NA 
				[country_code] => US 
				[country_code3] => USA 
				[country_name] => United States 
				[dma_code] => 807 
				[gmt_offset] => -25200 
				[ip] => 74.125.45.100 
				[is_dst] => 1 
				[latitude] => 37.4192 
				[longitude] => -122.057 
				[metro_code] => 807 
				[organization] => Google Inc. 
				[postal_code] => 94043 
				[region] => 06 
				[region_name] => California 
				[registry] => arin 
				[state] => CA 
				[tech_contact] => arin-contact@google.com 
				[timezone] => America/Los_Angeles 
			) 

	) 

And.... that's all, enjoy your day!