<?php
require_once('./library/utils.php');
header('Content-Type: application/json; charset=utf8');
$Parameter = isset($_GET['params'])?  $_GET['params']:null;
$ReturnData;
$setup = getSetting();

if($Parameter == null){
	exit('No Input!!!');
}

if($Parameter == 'settings'){
	$ReturnData = $setup;
}

if($Parameter == 'server'){
	$server = new ServiceTest($setup->ports);
	$ReturnData = $server->getResult();
}

if($Parameter == 'list'){
	$projectlist = array();
	$otherlist = array();
	foreach($setup->folder as $type => $detail){
		$list = new ProjectList($detail, $setup->hidefolder);
		foreach($list->getList('projects') as $project){
			$oneProject = new ProjectDetail($project,$detail);
			array_push($projectlist, $oneProject->getData());
		}
		foreach($list->getList('others') as $link){
			$oneLink = new LinkDetail($link, $detail);
			array_push($otherlist, $oneLink->getData());
		}
	}
	$ReturnData = array(
		'projects' => $projectlist,
		'others' => $otherlist
	);
}


exit(json_encode($ReturnData));
?>
