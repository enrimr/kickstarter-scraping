<?php
//http://proxylist.hidemyass.com/search-1305869#listable



function getProxy(){
	$proxies = array(); // Declaring an array to store the proxy list
 
	// Adding list of proxies to the $proxies array
	/*$proxies[] = '222.39.64.74:8118';  // Some proxies require user, password, IP and port number
	$proxies[] = '62.201.200.17:80';
	$proxies[] = '198.169.246.30:80';
	$proxies[] = '222.45.85.53:8118';  // Some proxies only require IP
	$proxies[] = '111.13.12.216:80';
	$proxies[] = '221.212.74.203:1818'; // Some proxies require IP and port number
	$proxies[] = '27.122.12.45:3128';*/
	
	$proxies[] = '149.255.107.87:80';
	$proxies[] = '78.110.163.108:80';
	$proxies[] = '23.239.64.40:80';
	$proxies[] = '107.173.209.34:80';

	// Choose a random proxy
	if (isset($proxies)) {  // If the $proxies array contains items, then
	    $proxy = $proxies[array_rand($proxies)];    // Select a random proxy from the array and assign to $proxy variable
	}

	echo " > ".$proxy;

	return $proxy;
}

?>