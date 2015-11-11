<?php
include_once("simple_html_dom.php");

$prefix = 'https://www.kickstarter.com';
// Create DOM from URL
$html = file_get_html($prefix.'/projects/katemats/the-spark-planner-achieve-all-your-goals-in-2016/comments');

//print_r($html);
//var_dump($html->find('.older_commentssafdsf'));

$continue = false;
$continueURL = "";
$page = 0;

// Init array with header
$header['name'] = "Name";
$header['profile'] = "Profile URL";
$header['picture'] = "Picture URL";
$comments[] = $header;

do {
	echo $page++;

	// Find all article blocks
	foreach($html->find('.comments .comment') as $comment) {
		echo $comment->find('.creator')->plaintext;
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

print_r($comments);


// Save as a CSV file
$fp = fopen('database.csv', 'w');

foreach ($comments as $campos) {
    fputcsv($fp, $campos, ";");
}

fclose($fp);