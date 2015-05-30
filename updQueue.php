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

function processQueue($TKDDB){
	$noRings = 5;
	$queueSlot = -2;
	while ($queueSlot != -1){
		$queueSlot = -1;
		$getCurrQueue = sprintf("SELECT rowID, ringNo, queueNo FROM rings WHERE completed = 0 ORDER BY ringNo ASC, queueNo ASC");
		mysql_select_db($database_TKDDB, $TKDDB);
		$Result1 = mysql_query($getCurrQueue, $TKDDB) or die(mysql_error());
		$row_RingQueue = mysql_fetch_assoc($Result1);
		$totalRows_RingQueue = mysql_num_rows($Result1);
		
		unset($ringQueue);
		$ringQueue[0] = 0;
		do{
			$ringQueue[$row_RingQueue['ringNo']]++;
		} while ($row_RingQueue = mysql_fetch_assoc($Result1));
		print_r($ringQueue);
		if ($ringQueue[0] > 0) {
			$queueMin = 3;
			for ($i = 1; $i <= $noRings; $i++){
				if ($ringQueue[$i] < $queueMin){ //Find Ring with Most Available
					$queueMin = $ringQueue[$i];
					$queueSlot = $i;
				}
			} //end for
			if ($queueSlot != -1) {
				//Available Ring Found...Now assign it.
				//First, get the next event to be assigned.
				$getQueue = sprintf("SELECT rowID FROM rings WHERE completed = 0 AND ringNo = 0 ORDER BY queueNo ASC LIMIT 1");
				mysql_select_db($database_TKDDB, $TKDDB);
				$Result2 = mysql_query($getQueue, $TKDDB) or die(mysql_error());
				$row_RingQueueN = mysql_fetch_assoc($Result2);
				$totalRows_RingQueueN = mysql_num_rows($Result2);
				//Then, assign it.
				$updQueue = sprintf("UPDATE rings SET ringNo=%s WHERE rowID=%s",GetSQLValueString($queueSlot,"int"),GetSQLValueString($row_RingQueueN['rowID'],"int"));
				mysql_select_db($database_TKDDB, $TKDDB);
				$Result3 = mysql_query($updQueue, $TKDDB) or die(mysql_error());
			}
		} //end if
	} //end while Processing

} //end processQueue

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE rings SET ringNo=%s, queueNo=%s, chung=%s, hong=%s, completed=%s, comments=%s WHERE `rowID`=%s",
                       GetSQLValueString($_POST['ringNo'], "int"),
                       GetSQLValueString($_POST['queueNo'], "double"),
                       GetSQLValueString($_POST['chung'], "text"),
                       GetSQLValueString($_POST['hong'], "text"),
                       GetSQLValueString(isset($_POST['completed']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['rowID'], "int"));

  mysql_select_db($database_TKDDB, $TKDDB);
  $Result1 = mysql_query($updateSQL, $TKDDB) or die(mysql_error());
    processQueue($TKDDB);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
  $insertSQL = sprintf("INSERT INTO rings (ringNo, queueNo, chung, hong, completed, comments) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ringNo'], "int"),
                       GetSQLValueString($_POST['queueNo'], "double"),
                       GetSQLValueString($_POST['chung'], "text"),
                       GetSQLValueString($_POST['hong'], "text"),
                       GetSQLValueString(isset($_POST['completed']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['comments'], "text"));

  mysql_select_db($database_TKDDB, $TKDDB);
  $Result1 = mysql_query($insertSQL, $TKDDB) or die(mysql_error());
  processQueue($TKDDB);
}

if ((isset($_POST['chkDel'])) && (isset($_POST['delrowID'])) && ($_POST['delrowID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM rings WHERE `rowID`=%s",
                       GetSQLValueString($_POST['delrowID'], "int"));

  mysql_select_db($database_TKDDB, $TKDDB);
  $Result1 = mysql_query($deleteSQL, $TKDDB) or die(mysql_error());
    processQueue($TKDDB);
}

mysql_select_db($database_TKDDB, $TKDDB);
$query_RingQueue = "SELECT * FROM rings WHERE completed = 0 ORDER BY ringNo ASC, queueNo ASC";
$RingQueue = mysql_query($query_RingQueue, $TKDDB) or die(mysql_error());
$row_RingQueue = mysql_fetch_assoc($RingQueue);
$totalRows_RingQueue = mysql_num_rows($RingQueue);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Queue</title>
<link href="oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationCheckbox.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationCheckbox.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr">

<div id="container">
  <div id="header">
    <h1 align="center"><a href="index.php"><img src="images/TKDLogo.gif" width="800" height="90" alt="Penn TKD" /></a></h1>
  <!-- end #header --></div>
  <div id="mainContent">
    <h1> Update Queue</h1>
    <p>Note: To push something onto the queue, set ringNo = 0 and leave queueNo as is.</p>
    
    <table border="1">
          <tr>
            <td>rowID</td>
            <td>ringNo</td>
            <td>queueNo</td>
            <td>chung</td>
            <td>hong</td>
            <td>compl</td>
            <td>comments</td>
            <td>Update</td>
            <td>Delete</td>
          </tr>
      <?php 
		  $highestQueueNo = -1.0;
		  
		  do { ?>
          <tr>
            <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
              <td><?php echo $row_RingQueue['rowID']; ?>
                  <input type="hidden" name="MM_update" value="form1" />
                  <input type="hidden" name="rowID" value="<?php echo $row_RingQueue['rowID']; ?>" /></td>
              <td><input type="text" name="ringNo" value="<?php echo htmlentities($row_RingQueue['ringNo'], ENT_COMPAT, 'utf-8'); ?>" size="2" /></td>
              <td><input type="text" name="queueNo" value="<?php echo htmlentities($row_RingQueue['queueNo'], ENT_COMPAT, 'utf-8');
			  
			  if ($row_RingQueue['queueNo'] > $highestQueueNo) {
			  	$highestQueueNo = $row_RingQueue['queueNo'];
			  }
			   ?>" size="10" /></td>
              <td><input type="text" name="chung" value="<?php echo htmlentities($row_RingQueue['chung'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
              <td><input type="text" name="hong" value="<?php echo htmlentities($row_RingQueue['hong'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
              <td><input type="checkbox" name="completed" value=""  <?php if (!(strcmp(htmlentities($row_RingQueue['completed'], ENT_COMPAT, 'utf-8'),""))) {echo "checked=\"checked\"";} ?> /></td>
              <td><input type="text" name="comments" value="<?php echo htmlentities($row_RingQueue['comments'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
              <td><input type="submit" value="Update" /></td>
            </form>
            <td><label> </label>
                <form id="form2" name="form2" method="post" action="">
                  <input type="hidden" name="delrowID" value="<?php echo $row_RingQueue['rowID']; ?>" />
                  <span id="sprycheckbox1">
                  <label>
                  <input type="checkbox" name="chkDel" id="chkDel" />
                  </label>
                  <span class="checkboxRequiredMsg">Please make a selection.</span></span>
                  <label>
                  <input type="submit" name="delete" id="delete" value="Delete" />
                  </label>
              </form></td>
          </tr>
          <?php } while ($row_RingQueue = mysql_fetch_assoc($RingQueue)); ?>
          <tr>
            <form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3">
              <td>&nbsp;</td>
              <td><input type="text" name="ringNo" value="0" size="2" /></td>
              <td><input type="text" name="queueNo" value="<?php echo $highestQueueNo+1; ?>" size="10" /></td>
              <td><input type="text" name="chung" value="" size="32" /></td>
              <td><input type="text" name="hong" value="" size="32" /></td>
              <td><input type="checkbox" name="completed" value="" /></td>
              <td><input type="text" name="comments" value="" size="32" /></td>
              <td><input type="submit" value="Insert" /></td>
              <input type="hidden" name="MM_insert" value="form3" />
            </form>
            <td>&nbsp;</td>
          </tr>

    </table>
        <!-- end #mainContent --></div>
  <div id="footer">
    <p align="center">&copy; 2009 Penn Taekwondo</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
<script type="text/javascript">
<!--
var sprycheckbox1 = new Spry.Widget.ValidationCheckbox("sprycheckbox1");
//-->
</script>
</body>
</html>
<?php
mysql_free_result($RingQueue);
?>
