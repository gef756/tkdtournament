<?php
 set_include_path(get_include_path(). PATH_SEPARATOR .$_SERVER['DOCUMENT_ROOT']);
require_once('support/error_handler.php');
require_once('Connections/TKDDB.php');

	
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
case "final":
      if ($theValue != ""){
		if ($theValue == 'false'){
			$theValue = "NULL";}
		if ($theValue == 'true'){
			$theValue = "'F'";
			}
		}
	else $theValue = "NULL";
break;
  }
  return $theValue;
}

	if (ob_get_length()) ob_clean();
	header('Expires: Fri, 25 Dec 1980 00:00:00 GMT');
	header('Last Modified: '.gmdate( 'D, d M Y H:i:s'). 'GMT');
	header('Pragma: no-cache');
	header('Content-Type: text/html');
	if ($_GET['action'] == "updateEvent"){
$cLScore = GetSQLValueString($_GET['cLScore'],'int');
$hLScore = GetSQLValueString($_GET['hLScore'],'int');
$cMScore = GetSQLValueString($_GET['cMScore'],'int');
$hMScore = GetSQLValueString($_GET['hMScore'],'int');
$cHScore = GetSQLValueString($_GET['cHScore'],'int');
$hHScore = GetSQLValueString($_GET['hHScore'],'int');
$lStatus = GetSQLValueString($_GET['lStatus'],'final');
$mStatus = GetSQLValueString($_GET['mStatus'],'final');
$hStatus = GetSQLValueString($_GET['hStatus'],'final');

$rowID = GetSQLValueString($_GET['rowID'],'int');

$chung = GetSQLValueString($_GET['chung'], "text");
$hong = GetSQLValueString($_GET['hong'], "text");
}
$ring = GetSQLValueString($_GET['ring'], "int");
$mMysqli = new mysqli(hostname_TKDDB, username_TKDDB, password_TKDDB, database_TKDDB);	
	$myList = '';

	if ($_GET['action'] == "updateEvent"){
		$query = "UPDATE rings SET cLScore=$cLScore, hLScore=$hLScore, cMScore=$cMScore, hMScore=$hMScore, cHScore=$cHScore, hHScore=$hHScore, lStatus=$lStatus, mStatus=$mStatus, hStatus=$hStatus WHERE rowID=$rowID;";
		$result = $mMysqli->query($query);
		header('MSQuery: '.$query);

	}
	//Punch Back all Data
		$query = "SELECT rowID, chung, hong, cLScore, hLScore, cMScore, hMScore, cHScore, hHScore, lStatus, mStatus, hStatus FROM rings WHERE completed=0 AND ringNo=$ring ORDER BY queueNo ASC LIMIT 1";
		$result = $mMysqli->query($query);
		$row = $result->fetch_assoc();

$response = 
		'<?xml version = "1.0" encoding = "UTF-8" standalone="yes"?>'.
		'<response><ring>';
		foreach ($row as $field => $value){
		$response .= '<dataInp>';
		$response .= '<fieldid>'.$field.'</fieldid>';

		if (strpos($field,'Status') !== false){
			$response .= '<type>checked</type>';
			if ($value == 'F') 
			$response .= '<result>checked</result>';
			else $response .='<result />';
}	
		else if (strpos($field,'hong') !== false || strpos($field, 'chung') !== false || strpos($field, 'rowID')){
			$response .= '<type>innerHTML</type>';
		$response .= '<result>'.$value.'</result>';}
		else{
			$response .='<type>value</type>';
			$response .= '<result>'.$value.'</result>';
		}
		$response .= '</dataInp>';
		}
		$response .= '</ring></response>';
	if (ob_get_length()) ob_clean();
	header('Content-type: text/xml');
	echo $response;
?>