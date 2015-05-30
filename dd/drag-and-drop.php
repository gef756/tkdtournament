<?php
	require_once('queuelist.class.php');
	$mRingNo = $_GET['ringNo'];
	$myEventsList = new EventsList($mRingNo);
	$action = $_GET['action'];
	$content = $_GET['content'];
	if (ob_get_length()) ob_clean();
	header('Expires: Fri, 25 Dec 1980 00:00:00 GMT');
	header('Last Modified: '.gmdate( 'D, d M Y H:i:s'). 'GMT');
	header('Pragma: no-cache');
	header('Content-Type: text/html');
	if ($action == "addNewEvent" && $content == "ConfirmAdd"){
		$myEventsList->addEvent($_GET['addChung'],$_GET['addHong'],$_GET['addCBID'],$_GET['addHBID'],$_GET['addFeedTo'],$_GET['addComments']);
	}
	else $myEventsList->process($content, $action);
	//Punch Back all Data
	$response = 
		'<?xml version = "1.0" encoding = "UTF-8" standalone="yes"?>'.
		'<response>';
	for ($i=0; $i <= 7; $i++){
		$eventsList = new EventsList($i);
		if ($i == 0) $iList = "eventsList";
		else $iList = 'ring'.$i.'List';
		$response .= '<ring><fieldid>'.$iList."</fieldid><result>";
		$response.= htmlentities($eventsList->BuildEventsList());
		$response .= "</result></ring>";
	}
		$response .= '</response>';
	if (ob_get_length()) ob_clean();
	header('Content-type: text/xml');
	echo $response;
	
/*//Only give back requested ring
$response = 
		'<?xml version = "1.0" encoding = "UTF-8" standalone="yes"?>'.
		'<response>';
		if ($mRingNo == 0) $iList = "eventsList";
		else $iList = 'ring'.$mRingNo.'List';
		$response .= '<ring><fieldid>'.$iList."</fieldid><result>";
		$response.= htmlentities($myEventsList->BuildEventsList());
		$response .= "</result></ring>";
		$response .= '</response>';
	if (ob_get_length()) ob_clean();
	header('Content-type: text/xml');
	echo $response; */
?>