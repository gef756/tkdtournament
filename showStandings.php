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

mysql_select_db($database_TKDDB, $TKDDB);
$query_Schools = "SELECT * FROM schools ORDER BY division ASC, totPoints DESC";
$Schools = mysql_query($query_Schools, $TKDDB) or die(mysql_error());
$row_Schools = mysql_fetch_assoc($Schools);
$totalRows_Schools = mysql_num_rows($Schools);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if (isset($_GET['refresh'])) {?>
<meta http-equiv="refresh" content="<?php echo $_GET['refresh']; ?>;url=/screen<?php echo $_GET['screen']; ?>.php" />
<?php } ?>
<title>Current Standings</title>
<link href="oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.divisionHead {background-color: #666;
color: #fff;
}
.pennStandings {background-color: #d3d3d3;
font-weight: bold;
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
    <h1> Current Standings</h1>
    <table width="100%" cellpadding="0" cellspacing="0">

      <tr style="background-color: #e8e8e8;">
        <td class="content" style="padding: 2px; border-bottom: 3px solid #ffffff;"><b>Rank</b></td>
        <td class="content" style="padding: 2px; border-bottom: 3px solid #ffffff;"><b>School</b></td>
        <td class="content" style="padding: 2px; border-bottom: 3px solid #ffffff;"><b>Prior</b></td>
        <td class="content" style="padding: 2px; border-bottom: 3px solid #ffffff;"><strong>Today</strong></td>
        <td class="content" style="padding: 2px; border-bottom: 3px solid #ffffff;"><b>Total</b></td>
      </tr>
     <?php
	 $currDivision = 0;
	 do{
//If there's a new division
	 if ($row_Schools['division'] != $currDivision) { ?>
      <tr style="background-color: #e8e8e8;">
        <td class="divisionHead" style="padding: 2px; border-bottom: 1px solid #ffffff;"><u>Division <?php echo $row_Schools['division']; ?></u></td>
        <td class="divisionHead" style="padding: 2px; border-bottom: 1px solid #ffffff;">&nbsp;</td>
        <td class="divisionHead" style="padding: 2px; border-bottom: 1px solid #ffffff;">&nbsp;</td>
        <td class="divisionHead" style="padding: 2px; border-bottom: 1px solid #ffffff;">&nbsp;</td>
        <td class="divisionHead" style="padding: 2px; border-bottom: 1px solid #ffffff;">&nbsp;</td>
      </tr>
	 <?php 
	 $currDivision = $row_Schools['division']; 
	 $rankCount = 1;
	 }
	 ?>
     <tr style="background-color: #e8e8e8;">
        <td class="content" style="padding: 2px; border-bottom: 1px solid #ffffff;"><?php echo $rankCount; ?></td>
        <td class="content" style="padding: 2px; border-bottom: 1px solid #ffffff;"><?php echo $row_Schools['schoolName']; ?></td>
        <td class="content" style="padding: 2px; border-bottom: 1px solid #ffffff;"><?php echo $row_Schools['prevPoints']; ?></td>
        <td class="content" style="padding: 2px; border-bottom: 1px solid #ffffff;"><?php echo $row_Schools['pennPoints']; ?></td>
        <td class="content" style="padding: 2px; border-bottom: 1px solid #ffffff;"><?php echo $row_Schools['totPoints']; ?></td>
      </tr>
     <?php
	 $rankCount++;
	 } while ( $row_Schools = mysql_fetch_assoc($Schools) )
	 ?>


    </table>
    <tr>


    <!-- end #mainContent --></div>
  <div id="footer">
    <p align="center">&copy; 2009 Penn Taekwondo</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($Schools);
?>
