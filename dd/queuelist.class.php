<?php set_include_path(get_include_path(). PATH_SEPARATOR .$_SERVER['DOCUMENT_ROOT']);
require_once('support/error_handler.php');
require_once('Connections/TKDDB.php');

class EventsList{
	private $mMysqli;
	private $ringNo;
	
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
	function __construct($mRingNo){
		$this->mMysqli = new mysqli(hostname_TKDDB, username_TKDDB, password_TKDDB, database_TKDDB);
		$this->ringNo = $mRingNo;
	}
	
	function __destruct(){
		$this->mMysqli->close();
	}
	
	public function BuildEventsList(){
		$myList = '';
		if ($this->ringNo == -1) $query = "SELECT * FROM rings WHERE completed=0 ORDER BY queueNo ASC";
		else $query = "SELECT * FROM rings WHERE completed=0 AND ringNo=$this->ringNo ORDER BY queueNo ASC";
		$result = $this->mMysqli->query($query);
		while ($row = $result->fetch_assoc()){
			$myList .='<li id="'.htmlentities($row['rowID']).'">' . '<table class="eventDisp">
<tr><th class="eventID">'.htmlentities($row['rowID']).'</th>
  <th colspan="2" class="chung">'.htmlentities($row['chung']).'</th><th class="spacer"></th>
  <th colspan="2" class="hong">'.htmlentities($row['hong']).'</th>
</tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" class="bracketID">('.htmlentities($row['cBracketID']).')</td>
    <td>&nbsp;</td>
    <td colspan="2" class="bracketID">('.htmlentities($row['hBracketID']).')</td>
  </tr>';
  if ($row['cLScore'].$row['lStatus'].$row['hLScore'].$row['cMScore'].$row['mStatus'].$row['hMScore'].$row['cHScore'].$row['hStatus'].$row['hHScore'] != ''){
  if ($row['cLScore'] > $row['hLScore']) $cLWin='cBack'; else $cLWin ='';
  if ($row['cLScore'] < $row['hLScore']) $hLWin='hBack';  else $hLWin ='';
  if ($row['cMScore'] > $row['hMScore']) $cMWin='cBack'; else $cMWin ='';
  if ($row['cMScore'] < $row['hMScore']) $hMWin='hBack';  else $hMWin ='';
  if ($row['cHScore'] > $row['hHScore']) $cHWin='cBack';  else $cHWin ='';
  if ($row['cHScore'] < $row['hHScore']) $hHWin='hBack'; else $hHWin ='';
  $myList .='
  <tr>
    <td class="wClass">L</td>
    <td class="compName">&nbsp;</td>
    <td class="score '.$cLWin.'">'.htmlentities($row['cLScore']).'</td>
    <td class="final '.$cLWin.$hLWin.'">'.htmlentities($row['lStatus']).'</td>
    <td class="score '.$hLWin.'">'.htmlentities($row['hLScore']).'</td>
    <td class="compName">&nbsp;</td>
  </tr>
  <tr>
    <td class="wClass">M</td>
    <td class="compName">&nbsp;</td>
    <td class="score '.$cMWin.'">'.htmlentities($row['cMScore']).'</td>
    <td class="final '.$cMWin.$hMWin.'">'.htmlentities($row['mStatus']).'</td>
    <td class="score '.$hMWin.'">'.htmlentities($row['hMScore']).'</td>
    <td class="compName">&nbsp;</td>
  </tr>
  <tr>
    <td class="wClass">H</td>
    <td class="compName">&nbsp;</td>
    <td class="score '.$cHWin.'">'.htmlentities($row['cHScore']).'</td>
    <td class="final '.$cHWin.$hHWin.'">'.htmlentities($row['hStatus']).'</td>
    <td class="score '.$hHWin.'">'.htmlentities($row['hHScore']).'</td>
    <td class="compName">&nbsp;</td>
  </tr>';
  }
  $myList .='<tr>
    <td>&nbsp;</td>
    <td colspan="2"><strong>Q </strong>: '.htmlentities($row['queueNo']).'</td>
    <td>&nbsp;</td>
    <td colspan="2" class="nextBracket">'.htmlentities($row['feedTo']).'<strong> : N</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="5">'.htmlentities($row['comments']).'</td>
    </tr>
</table></li>';
		}
		return $myList;
	}
	
function processQueue(){
	$noRings = 6;
	$noUnqueueableRings = 1;
	$queueSlot = -2;
	$queueMinSt = 3;
	$TKDDB = $this->mMysqli;
	while ($queueSlot != -1){
		$queueSlot = -1;
		$getCurrQueue = sprintf("SELECT rowID, ringNo, queueNo FROM rings WHERE completed = 0 ORDER BY ringNo ASC, queueNo ASC");
		$Result1 = $this->mMysqli->query($getCurrQueue) or die(mysql_error());
		$row_RingQueue = $Result1->fetch_assoc();
		$totalRows_RingQueue = $Result1->num_rows;

		unset($ringQueue);
		for ($i = 0; $i <= $noRings + $noUnqueueableRings; $i++){
			$ringQueue[$i] = 0;
		}
		do{
			header("TotalRows".$row_RingQueue['rowID'].": afds ".$row_RingQueue['ringNo']);
			$ringQueue[$row_RingQueue['ringNo']]++;
					
		} while ($row_RingQueue = $Result1->fetch_assoc());
		//print_r($ringQueue);
		if ($ringQueue[0] > 0) {
			$queueMin = $queueMinSt;
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
				$Result2 = $this->mMysqli->query($getQueue) or die(mysql_error());
				$row_RingQueueN = $Result2->fetch_assoc();
				$totalRows_RingQueueN = $Result2->num_rows;
				//Then, assign it.
				$maxQueueNoQ = $this->mMysqli->query("SELECT (MAX(queueNo) + 1) as queueNo FROM rings WHERE ringNo=$queueSlot");
				$maxQueueNoR = $maxQueueNoQ->fetch_assoc();
				$maxQueueNo = $maxQueueNoR['queueNo'];
				$updQueue = sprintf("UPDATE rings SET ringNo=%s, queueNo=$maxQueueNo WHERE rowID=%s",$this->GetSQLValueString($queueSlot,"int"),$this->GetSQLValueString($row_RingQueueN['rowID'],"int"));
				$Result3 = $this->mMysqli->query($updQueue) or die(mysql_error());
			}
		} //end if
	} //end while Processing

} //end processQueue
	
	public function process($content, $action){
		switch($action){
			case 'updateList':
				$queryPrevStr = ("SELECT (MAX(queueNo)+1) AS queueNo FROM rings");
				$new_order = explode('_', $content);
				for ($i=0; $i < count($new_order); $i++){
					$new_order[$i] = $this->mMysqli->real_escape_string($new_order[$i]);
					$result = $this->mMysqli->query("UPDATE rings SET queueNo='$i' WHERE rowID= '$new_order[$i]'");
				}
				break;
			case 'addNewEvent':
				$chung = trim($this->mMysqli->real_escape_string($content));
				if ($chung){
					$result = $this->mMysqli->query("SELECT (MAX(queueNo) + 1) AS queueNo FROM rings");
					$row = $result->fetch_assoc();
					$order = $row['queueNo'];
					header("OrderVal: $order");
					header("Chung: $chung");
					if (!$order) $order = 1;
					$result = $this->mMysqli->query("INSERT INTO rings (queueNo, chung, ringNo) VALUES ('$order','$chung','$this->ringNo')");
					header("DidItInsert: WTF? $result");
				}
				break;
			case 'delEvent':
				$content = trim($this->mMysqli->real_escape_string($content));
				$result = $this->mMysqli->query("DELETE FROM rings WHERE rowID='$content'");
				break;
			case 'complEvent':
				$content = trim($this->mMysqli->real_escape_string($content));
				$result = $this->mMysqli->query("UPDATE rings SET completed='1' WHERE rowID='$content'");
				break;
			case 'moveToRing':
					if ($this->ringNo != 0) $queueString ="SELECT (MAX(queueNo) +1) AS queueNo FROM rings WHERE ringNo='$this->ringNo'";
					else $queueString = "SELECT (MIN(queueNo) -1) AS queueNo FROM rings WHERE ringNo='$this->ringNo' AND completed='0'";
					$result = $this->mMysqli->query($queueString);
					$row = $result->fetch_assoc();
					$order = $row['queueNo'];
					if (!$order) $order = 1;
				$content = trim($this->mMysqli->real_escape_string($content));
				$result = $this->mMysqli->query("UPDATE rings SET ringNo='$this->ringNo', queueNo='$order' WHERE rowID='$content'");

				break;
		}
		$this->processQueue();
						$updatedList = $this->BuildEventsList();
							return $updatedList;
	}
	
	public function addEvent($addChung, $addHong='',$addCBID='',$addHBID='',$addFeedTo='',$addComments =''){
				$addChung = trim($this->mMysqli->real_escape_string($addChung));
				$addHong = trim($this->mMysqli->real_escape_string($addHong));
								$addCBID = trim($this->mMysqli->real_escape_string($addCBID));
												$addHBID = trim($this->mMysqli->real_escape_string($addHBID));
								$addFeedTo = trim($this->mMysqli->real_escape_string($addFeedTo));
				$addComments = trim($this->mMysqli->real_escape_string($addComments));
				if ($addChung){
					$result = $this->mMysqli->query("SELECT (MAX(queueNo) + 1) AS queueNo FROM rings");
					$row = $result->fetch_assoc();
					$order = $row['queueNo'];
					header("OrderValN: $order");
					header("Chung: $addChung");
					if (!$order) $order = 1;
					$queryIns = "INSERT INTO rings (queueNo, chung, ringNo, hong, cBracketID, hBracketID, feedTo, comments) VALUES ('$order','$addChung','$this->ringNo', '$addHong', '$addCBID','$addHBID','$addFeedTo','$addComments');";
					$result = $this->mMysqli->query($queryIns);
					header("DidItInsert: WTF? $queryIns");
				}
						$this->processQueue();
	}
}		
?>