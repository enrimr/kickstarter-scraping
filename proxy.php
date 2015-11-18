<?php
//http://proxylist.hidemyass.com/search-1305869#listable

function getProxy(){
	$range = 10;
	static $counter = 0;
	static $globalCounter = 0;
	$proxies = array(); // Declaring an array to store the proxy list
 
	// Adding list of proxies to the $proxies array
	/*$proxies[] = '149.255.107.87:80';
	$proxies[] = '78.110.163.108:80';
	$proxies[] = '23.239.64.40:80';
	$proxies[] = '107.173.209.34:80';*/

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
		echo " > total = $globalCounter - id = $counter - $proxy";
		$counter++;
		$globalCounter++;
	}

	return $proxy;
}

?>