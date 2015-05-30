<?php require_once('dd/queuelist.class.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Ring Queue</title>
<link href="dd/drag-and-drop.css" rel="stylesheet" type="text/css" />
<script src="dd/drag-and-drop.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<link href="oneColElsCtrHdr.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationCheckbox.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationCheckbox.css" rel="stylesheet" type="text/css" />
</head>

<body class="oneColElsCtrHdr" onload="startup();">

<div id="container">
  <div id="header">
    <h1 align="center"><a href="index.php"><img src="images/TKDLogo.gif" width="800" height="90" alt="Penn TKD" /></a></h1>
  <!-- end #header --></div>
  <div id="mainContent">
    <h1>Update Queue</h1>
<div id="trash">Drop to Delete</div>      
<div id="complete">Drop to Mark Complete</div>
           
		<table id="ringQueueTable" width="100%" border="1">
          <tr>
            <td><p class="ringTitle">I</p>
              <ul id="ring1List" class="sortableList" onmouseup="process('ring1List','updateList','1')"><?php $myEventsList1 = new EventsList('1'); echo $myEventsList1->BuildEventsList(); ?></ul>         <div id="ring1Drop" class="ringDrop">Drop for Ring 1</div>
            </td>
            <td><p class="ringTitle">II</p>
            <ul id="ring2List" class="sortableList" onmouseup="process('ring2List','updateList','2')"><?php $myEventsList2 = new EventsList('2'); echo $myEventsList2->BuildEventsList(); ?></ul><div id="ring2Drop" class="ringDrop">Drop for Ring 2</div></td>
            <td><p class="ringTitle">III</p>
            <ul id="ring3List" class="sortableList" onmouseup="process('ring3List','updateList','3')"><?php $myEventsList3 = new EventsList('3'); echo $myEventsList3->BuildEventsList(); ?></ul><div id="ring3Drop" class="ringDrop">Drop for Ring 3</div></td>
          </tr>
          <tr>
            <td><p class="ringTitle">IV</p>
            <ul id="ring4List" class="sortableList" onmouseup="process('ring4List','updateList','4')"><?php $myEventsList4 = new EventsList('4'); echo $myEventsList4->BuildEventsList(); ?></ul><div id="ring4Drop" class="ringDrop">Drop for Ring 4</div></td>
            <td><p class="ringTitle">V
              </p>
            <ul id="ring5List" class="sortableList" onmouseup="process('ring5List','updateList','5')"><?php $myEventsList5 = new EventsList('5'); echo $myEventsList5->BuildEventsList(); ?></ul><div id="ring5Drop" class="ringDrop">Drop for Ring 5</div></td>
            <td><p class="ringTitle">VI
              </p>
            <ul id="ring6List" class="sortableList" onmouseup="process('ring6List','updateList','6')"><?php $myEventsList6 = new EventsList('6'); echo $myEventsList6->BuildEventsList(); ?></ul><div id="ring6Drop" class="ringDrop">Drop for Ring 6</div></td>
          </tr>
        </table>
<div id="globs" style="float: right;">Queued Events per Ring? <input id="qepr"type="text" name="qepr"/></div><Br class="clearFloat" />
<div id="holdQueue" style="float: right;"><h2>Hold Queue</h2><ul id="ring7List" class="sortableList" onmouseup="process('ring7List','updateList','7')"><?php $myEventsList7 = new EventsList('7'); echo $myEventsList7->BuildEventsList(); ?></ul><div id="ring7Drop" class="ringDrop">Drop for HoldQueue</div></div>
	    <h2>Unassigned Events</h2><div id="ring0Drop" class="ringDrop">Drop to Throw on Top of Queue</div>
	<ul id="eventsList" class="sortableList" onmouseup="process('eventsList','updateList','0')"><?php $myEventsList = new EventsList('0'); echo $myEventsList->BuildEventsList(); ?>
		
	</ul><br />
         <h2>Add a New Event</h2>
         <div><ul style="list-style-type: none;"><li><table id="addEventTable" class="eventDisp">
<tr><th class="eventID">#</th>
  <th colspan="2" class="chung"><input name="addChung" type="text" id="addChung" maxlength="50" /></th><th class="spacer"></th>
  <th colspan="2" class="hong"><input name="addHong" type="text" id="addHong" maxlength="50" /></th>
</tr>
  <tr>
    <td><input type="button" name="submit" value="Add" style="text-decoration: underline; color: #CC0000" onclick="process('addEventTable','addNewEvent','0')" /></td>
    <td colspan="2" class="bracketID">(<input name="addCBID" type="text" id="addCBID" size="10" maxlength="10" />
    )</td>
    <td>&nbsp;</td>
    <td colspan="2" class="bracketID">(<input name="addHBID" type="text" id="addHBID" size="10" maxlength="10" />
    )</td>
  </tr>
<tr>
    <td>&nbsp;</td>
    <td colspan="2"><strong>Q </strong>: (automatic)</td>
    <td>&nbsp;</td>
    <td colspan="2" class="nextBracket"><input type="text" id="addFeedTo" name="addFeedTo" /><strong> : N</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="5"><strong>Notes</strong>: 
      <input name="addComments" type="text" id="addComments" size="75" maxlength="50" /></td>
    </tr>
</table>
         </li></ul>
           <?php /*<input type="text" id="txtNewEvent" name="txtNewEvent" size="30" maxlength="100" onkeydown="handleKey(event)" />*/ ?>
           
         </div>
         <br />


        <!-- end #mainContent --></div>
  <div id="footer">
    <p align="center">&copy; 2010 Penn Taekwondo</p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>