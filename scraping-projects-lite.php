<?php
error_reporting(E_ERROR | E_PARSE);
include_once("simple_html_dom.php");

$prefix = 'https://www.kickstarter.com';

for ($userId=60000; $userId < 3000000000; $userId++) { 
	// Create DOM from URL
	$continue = true;
	$page = 1;
	do {
		$html = file_get_html("$prefix/profile/$userId?page=$page");
		if (strcmp($html, "") !== 0) {

			/*foreach ($html->find('.project_item') as $project) {
				//echo "p ";
				//echo "\n >> $project->href";
				$projects[$project->href][] = $userId;
			}*/
			if ($html->find('.no-content')){
				$continue = false;
			} else {
				echo "UserId: $userId\n";
				$continue = false;
			}
		} else {
			$continue = false;
		}
	} while ( $continue );
}
