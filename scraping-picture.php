<?php
//https://www.kickstarter.com/projects/273274561/rekonect-notebook-the-magnetic-lifestyle/checkouts/51389755/thanks?event=create&recs=true
include_once("simple_html_dom.php");
//-------------------
//set POST variables
$url = 'https://www.tineye.com/search';
$fields = array(
	'url' => urlencode("https://ksr-ugc.imgix.net/avatars/59676/bill_avatar_full_size.original.jpg?v=1425468393&w=40&h=40&fit=crop&auto=format&q=92&s=ddaafe3577ea638451801eb02af63592")
);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);

var_dump($ch);

$http_redirect_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

$lal = file_get_html($http_redirect_url);
echo "\n\n --- -- \n";
foreach ($lal->find('.matches div div') as $searchResult) {
	echo "\n\n\n ===\n";
	//echo $searchResult;
	$title = $searchResult->find('div div h4', 0)->innertext;
	if (strcmp("twitter.com", $title) == 0) {
		print_r("$title\n");

		$url = $searchResult->find('div div h4', 0)->innertext;

		foreach ($$searchResult->find('p') as $paragraph) {
			echo $paragraph;
		}
	}
}

//close connection
curl_close($ch);