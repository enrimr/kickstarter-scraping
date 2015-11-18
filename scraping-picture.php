<?php
//https://www.kickstarter.com/projects/273274561/rekonect-notebook-the-magnetic-lifestyle/checkouts/51389755/thanks?event=create&recs=true
//http://tineye.com/search/0c515394065851b644c549612a05cf83606c7d6d?domain=wix.com&sort=score&order=asc

//http://www.jacobward.co.uk/using-proxies-for-scraping-with-php-curl/

include_once("simple_html_dom.php");
include_once("tor.php");
include_once("proxy.php");

// FORUMS.OVERCLOCKERSCLUB.COM
function isOverclockersShowtopic($url){
	$pos = stripos($url, "showtopic");
	return $pos !== false;
}
function getOverclockersClubProfileUrl($picture, $url){
	$web = file_get_html($url);
	foreach ($web->find('.basic_info .avatar a') as $user) {

		$imgSrc = $user->find('img',0)->src;

		$pos = strpos($imgSrc, $picture);
		if ($pos){

			$userProfileUrl = $user->href;
			return $userProfileUrl;
		}
	}
	return false;
}
function getOverclockersClubProfileTwitter($url){
	$web = file_get_html($url);

	foreach ($web->find('#custom_fields_social ul li') as $object) {
		$socialType = $object->find('.row_title',0)->innertext;
		if (strcmp("Twitter", $socialType) == 0){
			return $socialType = $object->find('.row_data',0)->innertext;;
		}
	}
	return false;
}

// TWITTER CHECK
function isTwitterStatus($url){
	$pos = stripos($url, "status");
	return $pos !== false;
}
function isTwitterProfile($url){
	$pos = substr_count($url, '/');
	return $pos <= 3;
}

function isTwitterPictureProfile($picture, $url){
	$web = file_get_html($url);
	$profileAvatarContainer = $web->find('.ProfileAvatar-container', 0);
	$pos = strpos($profileAvatarContainer->href, $picture);
	return $pos !== false;
}

function getProfileFromTwitterStatusComment($picture, $url){
	//4iv1lsmqhrav3n4voli0_bigger.jpeg
	//stream-item-header
	$web = file_get_html($url);
	foreach ($web->find('.stream-item-header') as $comment) {
		$imgSrc = $comment->find('a img',0)->src;

		$pos = strpos($imgSrc, $picture);
		if ($pos){

			$twitterProfile = $comment->find('a',0)->href;
			return $twitterProfile;
		}
	}
	return false;
}
function getProfileFromTwitterStatusFavorite($picture, $url){
	$web = file_get_html($url);
	foreach ($web->find('.avatar-row')->find('.stream-item-header') as $comment) {
		$imgSrc = $comment->find('a img',0)->src;

		$pos = strpos($imgSrc, $picture);
		if ($pos){

			$twitterProfile = $comment->find('a',0)->href;
			return $twitterProfile;
		}
	}
	return false;
}

// MAIN
function getInfoFromPicture($picture){

	$results = null;

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
	//$proxyauth = 'enrimr17dec:dog';

	//echo "\n\n\n PROXY CANDIDATE: ".$proxy."\n\n\n";

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

	curl_setopt($ch, CURLOPT_PROXY, $proxy);
	//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);

	//execute post
	//sleep(15);
	//$ch = getCurlConfiguration($url, true, $fields, $fields_string);

	$result = curl_exec($ch);
	//var_dump($result);

	$http_redirect_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
	//var_dump($http_redirect_url);

	if ($http_redirect_url){
		$web = file_get_html($http_redirect_url);
		//var_dump($http_redirect_url);
	} else {
		$web = str_get_html($result);
	}

	if ($web){
		//echo "\n > PROXY: $proxy\n";
		$results = array();
		foreach ($web->find('.matches div div') as $searchResult) {
			//echo $searchResult;
			$result = $searchResult->find('div div h4', 0);
			if ($result) {
		    	$title = $result->innertext;
		    	if (strcmp("twitter.com", $title) == 0) {
					print_r("Web: $title\n");

					$url = $searchResult->find('div div h4', 0)->innertext;

					$userImage = "";
					$twitterProfile = null;
					$oneResult = array();

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
								$pages[] = $link;

								if (isTwitterStatus($link)){
									echo "\n > Status: $link";

									$twitterProfile = getProfileFromTwitterStatusComment($userImage, $link);
									if ($twitterProfile){
										if (!isset($oneResult['twitter'])){
											$oneResult['twitter'] = "http://twitter.com".$twitterProfile;;
										} else {
											$oneResult['twitter_other'][] = "http://twitter.com".$twitterProfile;;
										}
									} else {
										$twitterProfile = getProfileFromTwitterStatusFavorite($userImage, $link);
										if ($twitterProfile){
											if (!isset($oneResult['twitter'])){
												$oneResult['twitter'] = "http://twitter.com".$twitterProfile;;
											} else {
												$oneResult['twitter_other'][] = "http://twitter.com".$twitterProfile;;
											}
										}
									}
								} else if (isTwitterProfile($link)){
									echo "\n > Profile: $link";
									if (isTwitterPictureProfile($userImage, $link)){

										if (!isset($oneResult['twitter'])){
											$oneResult['twitter'] = $link;
										} else {
											$oneResult['twitter_other'][] = $link;
										}
									}
								} else {
									echo "\n > Other: $link";
								}
							} 
						}
					}

					$oneResult['website']=$title;
					$oneResult['imageURL']=$userImage;
					$oneResult['links']=$pages;

					if (!in_array($oneResult, $results)){
			    		$results[] = $oneResult;
					}
				} else if (strcmp("forums.overclockersclub.com", $title) == 0) {
					print_r("Web: $title\n");

					$url = $searchResult->find('div div h4', 0)->innertext;

					$userImage = "";
					$twitterProfile = null;
					$oneResult = array();

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
								$pages[] = $link;
								if (isOverclockersShowtopic($link)){
									$profileUrl = getOverclockersClubProfileUrl($userImage,$link);
									if ($profileUrl !== false){
										$twitterProfile = getOverclockersClubProfileTwitter($profileUrl);

										if ($twitterProfile !== false){
											if (!isset($oneResult['twitter'])){
												$oneResult['twitter'] = "http://twitter.com/".trim($twitterProfile);
											} else {
												$oneResult['twitter_other'][] = "http://twitter.com/".trim($twitterProfile);
											}
										}

									}
								}
							} 
						}
					}

					$oneResult['website']=$title;
					$oneResult['imageURL']=$userImage;
					$oneResult['links']=$pages;

					if (!in_array($oneResult, $results)){
			    		$results[] = $oneResult;
					}
				} else {
					echo "\n\n - OTHER TITLE: $title\n";

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
								echo "\n\n      + Link: $link\n";
							}
						}
					}
				}
				
			} else {
		    	// handle this situation
			}
			
		}
	}

	//close connection
	curl_close($ch);

	return $results;
}



//$toPrint = getInfoFromPicture("https://ksr-ugc.imgix.net/avatars/59676/bill_avatar_full_size.original.jpg?v=1425468393&w=40&h=40&fit=crop&auto=format&q=92&s=ddaafe3577ea638451801eb02af63592");
//https://pbs.twimg.com/profile_images/584616732083511297/sQuTdMdi_bigger.jpg
//$toPrint = getInfoFromPicture("https://pbs.twimg.com/profile_images/584616732083511297/sQuTdMdi_bigger.jpg");
$toPrint = getInfoFromPicture("https://ksr-ugc.imgix.net/avatars/917635/greeny_white_background.original.PNG?v=1392304916&w=80&h=80&fit=crop&auto=format&q=92&s=118595030cd26f947d53a695c73c8963");
echo "\n";
print_r($toPrint);
