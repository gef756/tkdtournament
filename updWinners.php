<?php require_once('Connections/TKDDB.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) { //Update Winnings Table with Correct School
  $updateSQL = sprintf("UPDATE winnings SET winner=%s, winnerName=%s WHERE eventID=%s",
                       GetSQLValueString($_POST['winner'], "text"),
					   GetSQLValueString($_POST['winnerName'], "text"),
                       GetSQLValueString($_POST['eventID'], "int"));

  mysql_select_db($database_TKDDB, $TKDDB);
  $Result1 = mysql_query($updateSQL, $TKDDB) or die(mysql_error());
  
   //Clear all Point Values
	$getSchool = sprintf("UPDATE schools SET pennPoints=0, totPoints=0;" );
		 	$schoolUpds = mysql_query($getSchool, $TKDDB) or die(mysql_error());
  
   //Add Correct Values to Penn Points
  $getPoints = sprintf("SELECT winner, SUM(points) FROM winnings GROUP BY winner"); //First, add up points
    mysql_select_db($database_TKDDB, $TKDDB);
  $pointSums = mysql_query($getPoints, $TKDDB) or die(mysql_error());
  while ($row = mysql_fetch_array($pointSums)){
	if (isset($row['winner'])){
		$getSchool = sprintf("UPDATE schools SET pennPoints=%s WHERE schoolName=%s", //Then, update it
				GetSQLValueString($row['SUM(points)'],"int"),
				GetSQLValueString($row['winner'],"text"));
	}
	 	$schoolUpds = mysql_query($getSchool, $TKDDB) or die(mysql_error());
}

	//Go Through And Calculate Sums

  	 	$getOldPoints = sprintf("SELECT schoolName, prevPoints, pennPoints FROM schools");
	 	$oldPointSums = mysql_query($getOldPoints, $TKDDB) or die(mysql_error());
		$rowOld = mysql_fetch_array($oldPointSums);
		
		do{
		$getSchool = sprintf("UPDATE schools SET totPoints=%s WHERE schoolName=%s",
				GetSQLValueString($rowOld['pennPoints'] + $rowOld['prevPoints'],"int"),
				GetSQLValueString($rowOld['schoolName'],"text"));
	 	$schoolUpds = mysql_query($getSchool, $TKDDB) or die(mysql_error());  
  		} while ($rowOld = mysql_fetch_array($oldPointSums));
}

/*$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']); 
}*/

/* if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE winnings SET winner=%s WHERE eventID=%s",
                       GetSQLValueString($_POST['winner'], "text"),
                       GetSQLValueString($_POST['eventID'], "int"));

  mysql_select_db($database_TKDDB, $TKDDB);
  $Result1 = mysql_query($updateSQL, $TKDDB) or die(mysql_error());

  $updateGoTo = "updWinners.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
} */


mysql_select_db($database_TKDDB, $TKDDB);
$query_Winners = "SELECT * FROM winnings ORDER BY eventID ASC";
$Winners = mysql_query($query_Winners, $TKDDB) or die(mysql_error());
$row_Winners = mysql_fetch_assoc($Winners);
$totalRows_Winners = mysql_num_rows($Winners);

mysql_select_db($database_TKDDB, $TKDDB);
$query_Schools = "SELECT schoolName FROM schools WHERE isPresent = 1 ORDER BY schoolName ASC";
$Schools = mysql_query($query_Schools, $TKDDB) or die(mysql_error());
$row_Schools = mysql_fetch_assoc($Schools);
$totalRows_Schools = mysql_num_rows($Schools);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Winners</title>
<link href="oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1 align="center"><a href="index.php"><img src="images/TKDLogo.gif" width="800" height="90" alt="Penn TKD" /></a></h1>
  <!-- end #header --></div>
  <div id="mainContent">
    <h1> Update Winners</h1>
    <?php if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) { ?>
  <p style="font-family: centaur; font-weight: bold; color: #990000;"> Event <?php echo $_POST['eventID']; ?> updated with winner <?php echo $_POST['winnerName']; ?> from <?php echo $_POST['winner']; ?>.  </p>   <?php } ?>
    <p>Note: You have to hit update after the corresponding winner. You can't update multiple values at once.</p>
    <table border="1">
      <tr>
        <td><strong>eventID</strong></td>
        <td><strong>eventName</strong></td>
        <td><strong>ranking</strong></td>
        <td><strong>winner</strong></td>
        <td><strong>points</strong></td>
        <td><strong>gender</strong></td>
      </tr>
      <?php do { ?>
        <tr>
          <td><div align="center"><?php echo $row_Winners['eventID']; ?></div></td>
          <td><?php echo $row_Winners['eventName']; ?></td>
          <td><div align="center"><?php echo $row_Winners['ranking']; ?></div></td>
          <td>
            <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1">
<select name="winner">
                    <option value="" <?php if (!(strcmp("", htmlentities($row_Winners['winner'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>>None Yet</option>
                    <?php
do {  
?><option value="<?php echo $row_Schools['schoolName']?>"<?php if (!(strcmp($row_Schools['schoolName'], htmlentities($row_Winners['winner'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_Schools['schoolName']?></option>
                    <?php
} while ($row_Schools = mysql_fetch_assoc($Schools));
  $rows = mysql_num_rows($Schools);
  if($rows > 0) {
      mysql_data_seek($Schools, 0);
	  $row_Schools = mysql_fetch_assoc($Schools);
  }
?>
              </select>
<input type="text" name="winnerName" value="<?php echo htmlentities($row_Winners['winnerName'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
                    <input type="submit" value="Update" />
              <input type="hidden" name="MM_update" value="form1" />
              <input type="hidden" name="eventID" value="<?php echo $row_Winners['eventID']; ?>" />
              <input type="hidden" name="MM_update" value="form1" />
            </form></td>
          <td><div align="center"><?php echo $row_Winners['points']; ?></div></td>
          <td><?php echo $row_Winners['gender']; ?></td>
        </tr>
        <?php } while ($row_Winners = mysql_fetch_assoc($Winners)); ?>
    </table>
  <!-- end #mainContent --></div>
  <div id="footer">
    <p align="center">&copy; 2009 Penn Taekwondo</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
mysql_free_result($Winners);

mysql_free_result($Schools);
?>
