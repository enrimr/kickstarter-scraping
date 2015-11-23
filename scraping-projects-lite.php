<?php
error_reporting(E_ERROR | E_PARSE);
include_once("simple_html_dom.php");

$prefix = 'https://www.kickstarter.com';

$init = (isset($argv[1]) && is_numeric($argv[1])) ? $argv[1] : 0;
$interval = (isset($argv[2]) && is_numeric($argv[2])) ? $argv[2] : 1000000;

echo ">> $init + $interval\n";
for ($userId=$init; $userId < $init + $interval; $userId++) { 
	usleep(100000);
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
				echo "$userId\n";
				$continue = false;
			}
		} else {
			$continue = false;
		}
	} while ( $continue );
}
