<?php
include_once("simple_html_dom.php");

function isRealPicture($pictureIn){
	$pos = strpos($pictureIn, "missing_user_avatar.png");
	return $pos !== false;
}

$prefix = 'https://www.kickstarter.com';

$header['name'] = "Project URL";
$header['users'] = "Backers";
$header['count'] = "Total backers";
$projects[] = array();

// Add header to CSV file
$fp = fopen('database-projects.csv', 'w');
//fputcsv($fp, $header, ";");

for ($userId=0; $userId < 3000000000; $userId++) { 
	echo "\nUserId: $userId\n";
	// Create DOM from URL
	$continue = true;
	$page = 1;
	do {
		//echo "\nUserId: $userId - Page: $page\n";
		$html = file_get_html("$prefix/profile/$userId?page=$page");
		if (strcmp($html, "") !== 0) {

			foreach ($html->find('.project_item') as $project) {
				//echo "p ";
				//echo "\n >> $project->href";
				$projects[$project->href][] = $userId;
			}
			if ($html->find('.no-content') || $page > 40){
				$continue = false;
			} else {
				$page++;
			}
		} else {
			$continue = false;
		}
	} while ( $continue );

}

foreach ($projects as $key => $value) {
	$line['project'] = $key;
	$line['users'] = implode(",", $value);
    fputcsv($fp, $line, ";");
}
fclose($fp);
