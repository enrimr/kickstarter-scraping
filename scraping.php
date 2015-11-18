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
	//"PassionPlanner 2013" 		=> "/projects/angeliatrinidad/passion-planner-start-focusing-on-what-really-matt",
	//"PassionPlanner 2014"		=> "/projects/angeliatrinidad/passion-planner-the-one-place-for-all-your-thought",
	//"PassionPlanner Jun 2015"	=> "/projects/angeliatrinidad/passion-planner-the-life-coach-that-fits-in-your-b");
	"PassionPlanner Dec 2015"	=> "/projects/angeliatrinidad/passion-planner-get-one-give-one",
	"REconect Notebook"			=> "/projects/273274561/rekonect-notebook-the-magnetic-lifestyle",
	//"DOO" 						=> "/projects/336837899/my-doo-the-entrepreneurs-journal",
	//"Scrubby Notebook"			=> "/projects/1071068610/scrubby-notebooks",
	"Stylograph"				=> "/projects/oree/stylograph-take-your-ideas-from-paper-to-digital");

$header['project'] = "Project";
$header['name'] = "Name";
$header['profile'] = "Kickstarter Profile URL";
$header['picture'] = "Picture URL";
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
				$itemSocial = $item;
			} 
		    $itemSocial = $item;

		    if (!in_array($item, $comments)){
		    	if ($counter > 50) {return;}

		    	echo "\n ] ".$item['name'];
		    	if (strcmp($item['picture'], "") !== 0){
					echo "\n     - picture = ".$item['picture'];
					$resultForPicture = getInfoFromPicture($item['picture']);
					if ($resultForPicture){
						if (isset($resultForPicture[0]['twitter'])){
							$itemSocial['twitter'] = $resultForPicture[0]['twitter'];
						}

						if (isset($resultForPicture[0]['twitter_other'])){
							$itemSocial['twitter_other'] = $resultForPicture[0]['twitter_other'];
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
			echo "\nOlder comments URL: ".$prefix.$continueURL."\n\n";

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
