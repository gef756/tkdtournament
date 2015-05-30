<?php require_once('Connections/TKDDB.php'); ?>
<?php

function ordinalN($number) {
 if ($number == 1) return "1st";
 if ($number == 2) return "2nd";
 if ($number == 3) return "3rd";
 return $number;
}

if (!function_exists("GetSQLValueString")) {
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
  }
  return $theValue;
}
}

mysql_select_db($database_TKDDB, $TKDDB);
$query_Winners = "SELECT eventName, ranking, winner, winnerName, gender FROM winnings WHERE winner IS NOT NULL ORDER BY winner ASC";
$Winners = mysql_query($query_Winners, $TKDDB) or die(mysql_error());
$row_Winners = mysql_fetch_assoc($Winners);
$totalRows_Winners = mysql_num_rows($Winners);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if (isset($_GET['refresh'])) {?>
<meta http-equiv="refresh" content="<?php echo $_GET['refresh']; ?>;url=/screen<?php echo $_GET['screen']; ?>.php" />
<?php } ?>
<title>Winners By School</title>
<link href="oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1 align="center"><a href="index.php"><img src="images/TKDLogo.gif" width="800" height="90" alt="Penn TKD" /></a></h1>
  <!-- end #header --></div>
  <div id="mainContent">
    <h1>Winners By School</h1>
    <?php
	$currentWinner = "";
	do {
	if ($row_Winners['winner'] != $currentWinner) { echo $closeUL; ?> <h2> <?php echo $row_Winners['winner']; ?> </h2> <ul>
	<?php $closeUL = "</ul>"; $currentWinner = $row_Winners['winner'];}
	echo "<li>".$row_Winners['winnerName']." &mdash; ".$row_Winners['eventName'].", ".$row_Winners['gender'].", ".ordinalN($row_Winners['ranking'])." place</li>";
	} while ($row_Winners = mysql_fetch_assoc($Winners))?> </ul>
    <!-- end #mainContent --></div>
  <div id="footer">
    <p align="center">&copy; 2009 Penn Taekwondo</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($Winners);
?>
