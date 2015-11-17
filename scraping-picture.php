<?php
//https://www.kickstarter.com/projects/273274561/rekonect-notebook-the-magnetic-lifestyle/checkouts/51389755/thanks?event=create&recs=true
//http://tineye.com/search/0c515394065851b644c549612a05cf83606c7d6d?domain=wix.com&sort=score&order=asc

//http://www.jacobward.co.uk/using-proxies-for-scraping-with-php-curl/

include_once("simple_html_dom.php");
include_once("proxy.php");

function getInfoFromPicture($picture){

	// create the context...
	$context = stream_context_create($opts);

	//set POST variables
	$url = 'https://www.tineye.com/search';
	$fields = array(
		'url' => urlencode($picture)
	);

	$fields_string = "";
	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');

	//open connection
	$ch = curl_init();

	$proxy = getProxy();
	$proxyauth = 'enrimr17dec:dog';

	echo "\n\n\n PROXY: ".$proxy."\n\n\n";

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

	curl_setopt($ch, CURLOPT_PROXY, $proxy);
	curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);

	//execute post
	$result = curl_exec($ch);

	var_dump($result);

	$http_redirect_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

	if ($http_redirect_url){
		$web = file_get_html($http_redirect_url);
	} else {
		$web = str_get_html($result);
	}


	echo "-----> ".$http_redirect_url;


	echo "\n\n --- -- \n";
	foreach ($web->find('.matches div div') as $searchResult) {
		//echo $searchResult;
		$result = $searchResult->find('div div h4', 0);
		if ($result) {
	    	$title = $result->innertext;
	    	if (strcmp("twitter.com", $title) == 0) {
				print_r("Web: $title\n");

				$url = $searchResult->find('div div h4', 0)->innertext;

				$userImage = "";
				foreach ($searchResult->find('p') as $paragraph) {

					$pContent = $paragraph->innertext;

					// Check if it is a image
					$pos = strpos($pContent, "mage: "); // Si buscamos Image: nos da 0 que es === false
					if ($pos !== false){
						$userImage = $paragraph->find('a', 0)->title;
						echo "\nImage: ".$userImage;
					} else {
						// Check if it is a link
						$pos = strpos($pContent, "age: "); // Si buscamos Image: nos da 0 que es === false
						if ($pos !== false){
							$link = $paragraph->find('a', 0)->title;
							echo "\nLink: ".$link;
							$pages[] = $link;
						} 
					}
				}

				$oneResult['website']=$title;
				$oneResult['imageURL']=$userImage;
				$oneResult['links']=$pages;
				$results[] = $oneResult;
			}
		} else {
	    	// handle this situation
		}
		
	}

	//close connection
	curl_close($ch);

	return $results;
}

//$toPrint = getInfoFromPicture("https://ksr-ugc.imgix.net/avatars/59676/bill_avatar_full_size.original.jpg?v=1425468393&w=40&h=40&fit=crop&auto=format&q=92&s=ddaafe3577ea638451801eb02af63592");

//print_r($toPrint);
