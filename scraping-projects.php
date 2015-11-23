<?php
include_once("simple_html_dom.php");

$prefix = 'https://www.kickstarter.com';

function isRealPicture($pictureIn){
	$pos = strpos($pictureIn, "missing_user_avatar.png");
	return $pos !== false;
}

function pictureWithUserId($html, $userId){
	$fp = fopen('db-projects-pictures-1.csv', 'a');

	$picture = null;

	if (strcmp($html, "") !== 0) {
		$picture['userId'] = $userId;
		$picture['picture'] = urldecode($html->find('.avatar-image',0)->src);
		fputcsv($fp, $picture, ",");
	}

	fclose($fp);

	return $picture;
}

function projectsWithUserId($html, $userId){
	$page = $html;
	$backedProjects = null;
	$continue = true;
	$pageNumber = 1;
	$fp = fopen('db-projects-users-1.csv', 'a');
	do {
		//echo "\nUserId: $userId - Page: $page\n";
		if (strcmp($page, "") !== 0) {
			foreach ($page->find('.project_item') as $project) {
				//echo "p ";
				$oneProject['userId'] = $userId;
				$oneProject['name'] = $project->href;
				$backedProjects[] = $oneProject;
				fputcsv($fp, $oneProject, ",");
			}
			if ($page->find('.no-content') || $pageNumber > 40){
				$continue = false;
			} else {
				$pageNumber++;
				$page = file_get_html("$GLOBALS[prefix]/profile/$userId?page=$pageNumber");
			}
		} else {
			$continue = false;
		}
	} while ( $continue );

	fclose($fp);

	return $backedProjects;
}

function scrapProjectsAndUsers(){
	$allProjects = array();
	$pictures = array();

	$handle = fopen("logs/KS_1.log", "r");
	if ($handle) {
	    while (($line = fgets($handle)) !== false) {
	        echo "\nUserId: $line\n";
	        $userId = trim($line);
			$htmlDOM = file_get_html("$GLOBALS[prefix]/profile/$userId?page=1");

	        // Get picture
			$pictures[] = pictureWithUserId($htmlDOM, $userId);

	        // Get Projects
			$projects = projectsWithUserId($htmlDOM, $userId);
			if ($projects) {
				$allProjects = array_merge($allProjects, $projects);
			}
	    }
	    fclose($handle);
	} else {
	    // error opening the file.
	} 
}

scrapProjectsAndUsers();