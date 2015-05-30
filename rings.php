<?php require_once('Connections/TKDDB.php'); ?>
<?php
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
function getRingArr($ringVal, $TKDDB){
$database_TKDDB = "penntournament09";
	mysql_select_db($database_TKDDB, $TKDDB);
	$query_Ring1Res = "SELECT * FROM rings WHERE ringNo = $ringVal AND completed=0 ORDER BY queueNo ASC LIMIT 3";
	$RingRes = mysql_query($query_Ring1Res, $TKDDB) or die(mysql_error());
	return $RingRes;
}

function printEvInfo($chung, $hong){

	if (!isset($hong) || ($hong == "")){
		//Poomsae Event: Only show Chung, No Formatting
		echo '<p class="eventName">'.$chung.'</p>';
	}
	else {
		//Sparring Event
		echo '<p class="chung">'.$chung.'</p>';
		/*echo '<p class="vs"> vs. </p>';*/
		echo '<p class="hong">'.$hong.'</p>';
	}
}
function printRingInfo($ringNo, $TKDDB){
	$Ring1Res = getRingArr($ringNo, $TKDDB);
      $row_Ring1Res = mysql_fetch_assoc($Ring1Res);
	  $totalRows_Ring1Res = mysql_num_rows($Ring1Res);
		if ($totalRows_Ring1Res > 0) { // Show if recordset not empty 
           echo '<p class="secTitle">Up Now: </p>';
		   printEvInfo($row_Ring1Res['chung'],$row_Ring1Res['hong']);
		} // Show if recordset not empty
          

		 //On Deck
		  if ($totalRows_Ring1Res > 1) { // Show if recordset not empty 
           echo '<p class="secTitle">On Deck: </p>';
		   		  $row_Ring1Res = mysql_fetch_assoc($Ring1Res);
				  printEvInfo($row_Ring1Res['chung'],$row_Ring1Res['hong']);
		   echo '</p>';      } // Show if recordset not empty
          
		//Double Deck
		  if ($totalRows_Ring1Res > 2) { // Show if recordset not empty
           echo '<p class="secTitle">Double Deck: </p>';
		   		  $row_Ring1Res = mysql_fetch_assoc($Ring1Res);
				  printEvInfo($row_Ring1Res['chung'],$row_Ring1Res['hong']);
		   echo '</p>';      } // Show if recordset not empty
                    
		 if ($totalRows_Ring1Res == 0) { // Show if recordset empty
            echo '<p class="secTitle">(no information)</p>';
       		} // Show if recordset empty
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if (isset($_GET['refresh'])) {?>
<meta http-equiv="refresh" content="<?php echo $_GET['refresh']; ?>;url=/screen<?php echo $_GET['screen']; ?>.php" />
<?php } ?>
<title>Ring Information - Penn Tournament</title>
<link href="oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {
	font-size: 1.4em;
	margin: 0px 0px 5px 0px;
}
-->
</style>
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1 align="center"><a href="index.php"><img src="images/TKDLogo.gif" alt="Penn TKD" width="800" height="90" /></a></h1>
  <!-- end #header --></div>
  <div id="mainContent">
    <h1 align="center" class="style1">Ring Information</h1>
    <table width="100%" border="1" cellpadding="5px">
      <tr>
        <td colspan="3"><p class="ringName">Ring 1</p><?php echo printRingInfo(1, $TKDDB); ?></td>


  
        <td colspan="3"><p class="ringName">Ring 2</p><?php echo printRingInfo(2, $TKDDB); ?></td>
      </tr>
      <tr>
        <td colspan="2"><p class="ringName">Ring 3</p>
<?php echo printRingInfo(3, $TKDDB); ?></td>
        <td colspan="2"><p class="ringName">Ring 4</p>
<?php echo printRingInfo(4, $TKDDB); ?></td>
        <td colspan="2"><p class="ringName">Ring 5</p>
<?php echo printRingInfo(5, $TKDDB); ?></td>
      </tr>
    </table>
    <!-- end #mainContent --></div>
  <div id="footer">
    <p align="center">&copy; 2009 UPenn WTF Taekwondo</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($Ring1Res);

?>
