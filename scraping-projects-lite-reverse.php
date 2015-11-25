<?php
error_reporting(E_ERROR | E_PARSE);
include_once("simple_html_dom.php");

$init = (isset($argv[1]) && is_numeric($argv[1])) ? $argv[1] : 0;
$interval = (isset($argv[2]) && is_numeric($argv[2])) ? $argv[2] : 1000000;

echo ">> $init + $interval\n";
for ($userId=$init; $userId > $interval; $userId--) { 
	$html = file_get_html("https://www.kickstarter.com/profile/$userId?page=1");
	if (strcmp($html, "") !== 0) {
		if ($html->find('.no-content')){
			echo " - $userId\n";
		} else {
			echo "$userId\n";
		}
	}
}
