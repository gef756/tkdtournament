<?php require_once('../support/error_handler.php');
require_once('grid.class.php');
if (!isset($_GET['action'])){
	echo 'Server error: client command missing.';
	exit;
}
else{
	$action  = $_GET['action'];
}
$grid = new Grid($action);
if ($action =='FEED_GRID_PAGE'){
	$page = $_GET['page'];
	$grid->readPage($page);
}
else if ($action == 'UPDATE_ROW'){
	$id = $_GET['id'];
	//$eventName = $_GET['eventName'];
	//$ranking = $_GET['ranking'];
	//$gender = $_GET['gender'];
	$winner = $_GET['winner'];
	$winnerName = $_GET['winnerName'];
	//$points = $_GET['points'];
	$grid->updateWinningsRecord($id, $winner, $winnerName);
//echo "Query Result: ".$result;
}
else echo 'Server error: client command unrecognized.';

if (ob_get_length()) ob_clean();
header('Expires: Fri, 25 Dec 1980 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s'). 'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: text/xml');
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<data><action>'.$action.'</action>';
echo $grid->getParamsXML();
echo $grid->getGridXML();
echo '</data>'; ?>