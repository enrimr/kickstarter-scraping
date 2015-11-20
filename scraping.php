<?php
include_once("simple_html_dom.php");
include_once("scraping-picture.php");

function isRealPicture($pictureIn){
	$pos = strpos($pictureIn, "missing_user_avatar.png");
	return $pos !== false;
}

$prefix = 'https://www.kickstarter.com';

$projects = array(
	"SparkPlanner" 				=> "/projects/katemats/the-spark-planner-achieve-all-your-goals-in-2016",
	"SparkNotebook"				=> "/projects/katemats/spark-notebook-a-place-for-your-life-plans-and-gre",
	"PassionPlanner 2013" 		=> "/projects/angeliatrinidad/passion-planner-start-focusing-on-what-really-matt",
	"PassionPlanner 2014"		=> "/projects/angeliatrinidad/passion-planner-the-one-place-for-all-your-thought",
	"PassionPlanner Jun 2015"	=> "/projects/angeliatrinidad/passion-planner-the-life-coach-that-fits-in-your-b",
	"PassionPlanner Dec 2015"	=> "/projects/angeliatrinidad/passion-planner-get-one-give-one",
	"REconect Notebook"			=> "/projects/273274561/rekonect-notebook-the-magnetic-lifestyle",
	"DOO" 						=> "/projects/336837899/my-doo-the-entrepreneurs-journal",
	"Scrubby Notebook"			=> "/projects/1071068610/scrubby-notebooks",
	"Stylograph"				=> "/projects/oree/stylograph-take-your-ideas-from-paper-to-digital",
	"BasicsNotebook"			=> "/projects/1131502390/the-basics-notebook-simplify-and-improve-your-life");

$header['project'] = "Project";
$header['name'] = "Name";
$header['profile'] = "Kickstarter Profile URL";
$header['picture'] = "Picture URL";
$header['twitter'] = "Twitter";
$header['twitter_other'] = "Twitter Other";
$header['plus.google.com-links'] = "plus.google.com";
$header['www.facebook.com-links'] = "www.facebook.com";
$header['www.youtube.com-links'] = "www.youtube.com";
$header['www.kickstarter.com-links'] = "www.kickstarter.com";
$header['www.pinterest.com-links'] = "www.pinterest.com";
$header['medium.com-links'] = "medium.com";
$header['tumblr.com-links'] = "tumblr.com";
$comments[] = $header;

// Add header to CSV file
$fp = fopen('database.csv', 'w');
fputcsv($fp, $header, ";");

foreach ($projects as $projectName => $projectURL) {

	// Create DOM from URL
	$html = file_get_html($prefix.$projectURL.'/comments');

	$continue = false;
	$continueURL = "";
	$page = 0;
	$counter = 0;

	echo "\n\n Project: $projectName\n~~~~~~~~~~~~~~~~~~~~\n";
	
	do {
		echo $page++;

		// Find all article blocks
		foreach($html->find('.comments .comment') as $comment) {
			$item = array();
			$item['project'] = $projectName;
		    $item['name'] = $comment->find('.author', 0)->innertext;
		    $item['profile'] = str_replace("amp;", "", $prefix.$comment->find('.author', 0)->href);
		    $item['picture'] = str_replace("amp;", "", $comment->find('img', 0)->src);
		    if (isRealPicture($item['picture'])){
				$item['picture'] = "";
			} 
		    $itemSocial = $item;
		    $itemSocial['twitter'] = "";
			$itemSocial['twitter_other'] = "";
			$itemSocial['plus.google.com-links'] = "";
			$itemSocial['www.facebook.com-links'] = "";
			$itemSocial['www.youtube.com-links'] = "";
			$itemSocial['www.kickstarter.com-links'] = "";
			$itemSocial['www.pinterest.com-links'] = "";
			$itemSocial['medium.com-links'] = "";
			$itemSocial['tumblr.com-links'] = "";

		    if (!in_array($item, $comments)){
		    	//if ($counter > 50) {return;}

		    	//echo "\n ] ".$item['name'];
		    	if (strcmp($item['picture'], "") !== 0){
					//echo "\n     - picture = ".$item['picture'];
					$resultForPicture = getInfoFromPicture($item['picture']);
					if ($resultForPicture){
						foreach ($resultForPicture as $onePictureResult) {
							if (isset($onePictureResult['twitter'])){
								$itemSocial['twitter'] = $onePictureResult['twitter'];
							}

							if (isset($onePictureResult['twitter_other'])){
								$itemSocial['twitter_other'] = $onePictureResult['twitter_other'];
							}

							if (isset($onePictureResult['links'])){
								$key = $onePictureResult['website']."-links";
								$itemSocial[$key] = implode(", ", $onePictureResult['links']);
							}
						}
						
					}
				}

		    	$comments[] = $item;
		    	$itemSocials[] = $itemSocial;
		    	fputcsv($fp, $itemSocial, ";");
			} else {
				
			}
		}

		$continueURL = $html->find('.older_comments', 0)->href;

		if (strcmp("", $continueURL) !== 0){
			//echo "\nOlder comments URL: ".$prefix.$continueURL."\n\n";

			// Update the url to get
			$html = file_get_html($prefix.$continueURL);

			$continue = true;
		} else {
			echo "\nThere are no more comments";
			$continue = false;
		}

	} while ($continue);
}

//print_r($comments);



/*foreach ($comments as $item) {
    fputcsv($fp, $item, ";");
}*/

fclose($fp);
