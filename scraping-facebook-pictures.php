<?php

$init = (isset($argv[1]) && is_numeric($argv[1])) ? $argv[1] : 0;
$end = (isset($argv[2]) && is_numeric($argv[2])) ? $argv[2] : 333;

function curlGetWithUrl($url, $proxy = null, $proxyAuth = null){

	$ch = curl_init();

	curl_setopt($ch,CURLOPT_URL, $url);

	if ($proxy) {
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		if ($proxyAuth){
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
		}
	}
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	
	//execute post
	$raw = curl_exec($ch);

	$http_redirect_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	$result['code'] = $http_code;
	$result['image'] = $raw;

	curl_close($ch);

	return $result;
}

//https://graph.facebook.com/564035059/picture?type=large
// TOTAL: 120199999999999999
function scrapFacebookProfilePictures(){
	for($userId = $GLOBALS['init']; $userId < $GLOBALS['end']; $userId++){
		$return =  curlGetWithUrl("https://graph.facebook.com/$userId/picture?type=large");
		$saveto = "results/facebook/pictures/$userId.jpg";
		if ($return['code'] < 400){
			if(file_exists($saveto)){
			    unlink($saveto);
			}
			$fp = fopen($saveto,'x');
			fwrite($fp, $return['image']);
		}
	}
}

scrapFacebookProfilePictures();