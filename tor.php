<?php
function getCurlConfiguration($url, $post = false, $fields = null, $fields_string = ""){

	$ip = '127.0.0.1';
	$port = '9051';
	$auth = 'PASSWORD';
	//$auth = '16:872860B76453A77D60CA2BB8C1A7042072093276A3D701AD684053EC4C';
	$command = 'signal NEWNYM';
	 
	$fp = fsockopen($ip,$port,$error_number,$err_string,10);
	if(!$fp) { echo "ERROR: $error_number : $err_string";
	    return false;
	} else {
	    fwrite($fp,"AUTHENTICATE \"".$auth."\"\n");
	    $received = fread($fp,512);
	    //echo " > 1. $received";
	    fwrite($fp,$command."\n");
	    $received = fread($fp,512);
	    //echo " > 2. $received";
	}
	 
	fclose($fp);
	 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1:9050");
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);

	if ($post){
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	}

	$response = curl_exec($ch);
	//echo $response;

	return $ch;
}

//$web = getWeb("http://whatismyip.org");
//echo $web;
