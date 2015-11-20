<?php
//http://proxylist.hidemyass.com/search-1305869#listable

function getProxy(){
	$range = 35;
	static $counter = 0;
	static $globalCounter = 0;
	$proxies = array(); // Declaring an array to store the proxy list
 
	// Adding list of proxies to the $proxies array pass enrimr17dec:dog
	/*$proxies[] = '149.255.107.87:80';
	$proxies[] = '78.110.163.108:80';
	$proxies[] = '23.239.64.40:80';
	$proxies[] = '107.173.209.34:80';*/


	// FREE 1
	$proxies[] = '185.28.193.95:8080'; // SI
	$proxies[] = '197.159.142.97:8080'; // SI
	$proxies[] = '218.200.66.196:8080'; // SI
	$proxies[] = '220.248.224.242:8089'; // SI
	$proxies[] = '207.91.10.234:8080'; // SI
	$proxies[] = '185.28.193.95:8080'; // SI
	$proxies[] = '202.106.16.36:3128'; // SI
	$proxies[] = '205.189.170.150:80'; //SI
	$proxies[] = '163.177.79.4:8101'; // SI
	$proxies[] = '185.6.55.52:8080'; // SI

	// FREE 2
	$proxies[] = '183.238.80.240:3128'; // SI
	$proxies[] = '175.43.123.1:55336'; //SI
	$proxies[] = '95.109.117.232:8085'; //SI
	$proxies[] = '89.22.132.32:8080'; //SI
	$proxies[] = '123.125.8.253:1209'; //SI
	$proxies[] = '82.114.86.118:8080'; //SI
	$proxies[] = '82.117.163.74:8080'; //SI
	$proxies[] = '86.107.110.120:8000'; //SI
	$proxies[] = '65.18.103.253:3128'; //SI
	$proxies[] = '109.121.146.159:8080'; //SI
	$proxies[] = '185.28.193.95:8080'; // SI
	$proxies[] = '94.181.34.64:80'; // SI
	$proxies[] = '50.30.35.62:3128'; // SI
	$proxies[] = '183.238.80.240:3128'; //SI
	$proxies[] = '163.177.79.4:8104'; // SI
	$proxies[] = '163.177.79.4:8107'; // SI
	$proxies[] = '60.29.248.142:8080'; // SI
	$proxies[] = '113.4.156.73:3128'; // SI
	$proxies[] = '31.170.179.35:8080'; //SI
	$proxies[] = '193.37.152.186:3128'; //SI

	/* NO SÉ $proxies[] = '112.15.116.183:8123';
	$proxies[] = '190.204.109.70:8080';
	$proxies[] = '112.15.87.94:8123';
	$proxies[] = '41.205.11.112:8080';
	$proxies[] = '117.135.241.81:8080';
	$proxies[] = '58.23.16.245:80';
	$proxies[] = '110.45.135.229:8080';
	$proxies[] = '186.28.252.203:8089';
	$proxies[] = '222.88.236.236:843';
	$proxies[] = '163.177.155.2:8088';*/



	// Choose a random proxy
	/*if (isset($proxies)) {  // If the $proxies array contains items, then
	    $proxy = $proxies[array_rand($proxies)];    // Select a random proxy from the array and assign to $proxy variable
	}*/

	$proxy = null;

	if (isset($proxies)) {  // If the $proxies array contains items, then
		$index = round($counter/$range, 0, PHP_ROUND_HALF_DOWN);

		// To get the index inside the array range
		if ($index >= count($proxies)) {
			$index = 0;
			$counter = 0;
		}

	    $proxy = $proxies[$index];    // Select a random proxy from the array and assign to $proxy variable
		//echo " > total = $globalCounter - id = $counter - $proxy";
		$counter++;
		$globalCounter++;
	}

	return $proxy;
}

?>