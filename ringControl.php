<?php set_include_path(get_include_path(). PATH_SEPARATOR .$_SERVER['DOCUMENT_ROOT']);
require_once('support/error_handler.php');
require_once('Connections/TKDDB.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ring <?php echo $_GET['ringNo']; ?> Control</title>
<link href="oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
<style>
.compName{
width: 0px;
}
</style>
<?php

$mMysqli = new mysqli(hostname_TKDDB, username_TKDDB, password_TKDDB, database_TKDDB);	
$ringNo = $_GET['ringNo'];	
	$myList = '';
		$query = "SELECT * FROM rings WHERE completed=0 AND ringNo=$ringNo ORDER BY queueNo ASC LIMIT 1";
		$result = $mMysqli->query($query);
		$row = $result->fetch_assoc()
?>
<script src="dd/updateringsta.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>
<body class="oneColElsCtrHdr"  >

<div id="container">
  <div id="header">
    <h1 align="center"><a href="index.php"><img src="images/TKDLogo.gif" width="800" height="90" alt="Penn TKD" /></a></h1>
  <!-- end #header --></div>
  <div id="mainContent">
<h2>Welcome to Ring <?php echo $_GET['ringNo']; ?></h2>
<div class="ringControl">

  <ul style="list-style: none;">
<form id="form1" method="post" action=""><li id="<?php echo ($row['rowID']); ?>"><table class="eventDisp">
<tr><th class="eventID" id="rowID"><?php echo $row['rowID']; ?></th>
  <th colspan="2" class="chung" id="chung"><?php echo $row['chung']; ?></th><th class="spacer"></th>
  <th colspan="2" class="hong" id="hong"><?php echo $row['hong']; ?></th>
</tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" class="bracketID">(<?php echo $row['cBracketID'];?>)</td>
    <td><div align="center">F</div></td>
    <td colspan="2" class="bracketID">(<?php echo $row['hBracketID'];?>)</td>
  </tr><?php
  if ($row['cLScore'] > $row['hLScore']) $cLWin='cBack'; else $cLWin ='';
  if ($row['cLScore'] < $row['hLScore']) $hLWin='hBack';  else $hLWin ='';
  if ($row['cMScore'] > $row['hMScore']) $cMWin='cBack'; else $cMWin ='';
  if ($row['cMScore'] < $row['hMScore']) $hMWin='hBack';  else $hMWin ='';
  if ($row['cHScore'] > $row['hHScore']) $cMWin='cBack';  else $cHWin ='';
  if ($row['cHScore'] < $row['hHScore']) $hMWin='hBack'; else $hHWin ='';
?>
  <tr>
    <td class="wClass">L</td>
    <td class="compName">Lightweight</td>
    <td class="score '.$cLWin.'"><span id="sprytextfield1">
    <label>
    <input name="cLScore" type="text" id="cLScore" onchange="processUpdate(<?php echo $_GET['ringNo']; ?>)"value="<?php echo $row['cLScore']; ?>" />
    </label>
    <span class="textfieldInvalidFormatMsg">Invalid format.</span></span> </td>
    <td class="final '.$cLWin.$hLWin.'"> <input name="lStatus" type="checkbox" id="lStatus" onchange="processUpdate(<?php echo $_GET['ringNo']; ?>)"<?php if ($row['lStatus']=='F') echo 'checked="checked"'; ?>/></td>
    <td class="score '.$hLWin.'"><p><span id="sprytextfield2">
      <input type="text" name="hLScore" id="hLScore" /value="<?php echo $row['hLScore']; ?>" onchange="processUpdate(<?php echo $_GET['ringNo']; ?>)"/>
      <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></p>
      </td>
    <td class="compName">Lightweight</td>
  </tr>
  <tr>
    <td class="wClass">M</td>
    <td class="compName">Middleweight</td>
    <td class="score '.$cMWin.'"><span id="sprytextfield3">
      <input type="text" name="cMScore" id="cMScore" value="<?php echo $row['cMScore']; ?>"onchange="processUpdate(<?php echo $_GET['ringNo']; ?>)" />
      <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    <td class="final '.$cMWin.$hMWin.'"><label>
      <input name="mStatus" type="checkbox" id="mStatus" <?php if ($row['mStatus']=='F') echo 'checked="checked"'; ?>onchange="processUpdate(<?php echo $_GET['ringNo']; ?>)"/>
    </label></td>
    <td class="score '.$hMWin.'"><span id="sprytextfield4">
    <input type="text" name="hMScore" id="hMScore" value="<?php echo $row['hMScore']; ?>"onchange="processUpdate(<?php echo $_GET['ringNo']; ?>)"/>
    <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    <td class="compName">Middleweight</td>
  </tr>
  <tr>
    <td class="wClass">H</td>
    <td class="compName">Heavyweight</td>
    <td class="score '.$cHWin.'"><p><span id="sprytextfield5">
      <input type="text" name="cHScore" id="cHScore" value="<?php echo $row['cHScore']; ?>"onchange="processUpdate(<?php echo $_GET['ringNo']; ?>)"/>
      <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></p>
      </td>
    <td class="final '.$cHWin.$hHWin.'">
  <p>
    <label>
      <input name="hStatus" type="checkbox" id="hStatus" <?php if ($row['hStatus']=='F') echo 'checked="checked"'; ?>onchange="processUpdate(<?php echo $_GET['ringNo']; ?>)"/>
    </label>
  </p></td>
    <td class="score '.$hHWin.'"><span id="sprytextfield6">
    <input type="text" name="hHScore" id="hHScore" value="<?php echo $row['hHScore']; ?>" onchange="processUpdate(<?php echo $_GET['ringNo']; ?>)"/>
    <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    <td class="compName">Heavyweight</td>
  </tr><tr>
    <td><input type="button" name="refreshMe" id="refreshMe" value="FUpdate" onclick="processUpdate(<?php echo $_GET['ringNo']; ?>)" /></td>
    <td colspan="2"><strong>Q </strong>: <?php echo $row['queueNo']; ?></td>
    <td>&nbsp;</td>
    <td colspan="2" class="nextBracket cBack"><?php echo $row['feedTo']; ?><strong> : N</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="5"></td>
    </tr>
</table>
</form></li>
  </ul>
  </div>
  </div>
</div>
  </div>
  <script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {validateOn:["blur", "change"], isRequired:false});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "integer", {isRequired:false, validateOn:["blur", "change"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer", {validateOn:["blur", "change"], isRequired:false});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "integer", {validateOn:["blur", "change"], isRequired:false});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "integer", {validateOn:["blur", "change"], isRequired:false});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "integer", {isRequired:false, validateOn:["blur", "change"]});
//-->
</script>
</body>
</html>
