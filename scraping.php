<?php
include_once("simple_html_dom.php");

$prefix = 'https://www.kickstarter.com';

$projects = array(
	"SparkPlanner" 				=> "/projects/katemats/the-spark-planner-achieve-all-your-goals-in-2016",
	"PassionPlanner 2013" 		=> "/projects/angeliatrinidad/passion-planner-start-focusing-on-what-really-matt",
	"PassionPlanner 2014"		=> "/projects/angeliatrinidad/passion-planner-the-one-place-for-all-your-thought",
	"PassionPlanner Jun 2015"	=> "/projects/angeliatrinidad/passion-planner-the-life-coach-that-fits-in-your-b",
	"PassionPlanner Dec 2015"	=> "/projects/angeliatrinidad/passion-planner-get-one-give-one",
	"REconect Notebook"			=> "/projects/273274561/rekonect-notebook-the-magnetic-lifestyle",
	"DOO" 						=> "/projects/336837899/my-doo-the-entrepreneurs-journal");

$header['project'] = "Project";
$header['name'] = "Name";
$header['profile'] = "Kickstarter Profile URL";
$header['picture'] = "Picture URL";
$comments[] = $header;

foreach ($projects as $projectName => $projectURL) {
	// Create DOM from URL
	$html = file_get_html($prefix.$projectURL.'/comments');

	//print_r($html);
	//var_dump($html->find('.older_commentssafdsf'));

	$continue = false;
	$continueURL = "";
	$page = 0;

	do {
		echo $page++;

		// Find all article blocks
		foreach($html->find('.comments .comment') as $comment) {
			$item['project'] = $projectName;
		    $item['name'] = $comment->find('.author', 0)->innertext;
		    $item['profile'] = $prefix.$comment->find('.author', 0)->href;
		    $item['picture'] = $comment->find('img', 0)->src;

		    if (!in_array($item, $comments)){
		    	$comments[] = $item;
			}
		}

		$continueURL = $html->find('.older_comments', 0)->href;

		if (strcmp("",$continueURL) !== 0){
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

$fp = fopen('database.csv', 'w');

foreach ($comments as $item) {
    fputcsv($fp, $item, ";");
}

fclose($fp);
