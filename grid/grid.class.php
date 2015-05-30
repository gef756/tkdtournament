<?php require_once('../Connections/TKDDB.php');
session_start();

class Grid{
	public $mTotalPages;
public $mItemsCount;
public $mReturnedPage;
private $mMysqli;
private $grid;

function __construct(){
	$this->mMysqli = new mysqli(hostname_TKDDB, username_TKDDB, password_TKDDB, database_TKDDB);
	$this->mItemsCount = $this->countAllRecords();
}

function __destruct(){
	$this->mMysqli->close();
}

public function readPage($page){
	$queryString = $this->createSubpageQuery('SELECT * FROM winnings ORDER BY eventID ASC', $page);
	if ($result = $this->mMysqli->query($queryString)){
		while ($row = $result->fetch_assoc()){
			$this->grid .='<row>';
			foreach($row as $name=>$val)
				$this->grid .= '<'.$name.'>'.htmlentities($val).'</'. $name .'>';
			$this->grid .='</row>';
		}
		$result->close();
	}
}

public function updateWinningsRecord($id, $winner, $winnerName){
	$id = $this->mMysqli->real_escape_string($id);
	//$eventName = $this->mMysqli->real_escape_string($eventName);
//$ranking = $this->mMysqli->real_escape_string($ranking);
//$gender = $this->mMysqli->real_escape_string($gender);
$winner = $this->mMysqli->real_escape_string($winner);
$winnerName = $this->mMysqli->real_escape_string($winnerName);
//$points = $this->mMysqli->real_escape_string($points);
$queryString = "UPDATE winnings SET winner='$winner', winnerName='$winnerName' WHERE eventID='$id'";
$this->mMysqli->query($queryString);
//return $queryString;
}

public function getParamsXML(){
	$previous_page = ($this->mReturnedPage == 1)? '': $this->mReturnedPage-1;
	$next_page = ($this->mTotalPages == $this->mReturnedPage) ? '': $this->mReturnedPage + 1;
	return "<params><returned_page>".$this->mReturnedPage."</returned_page><total_pages>".$this->mTotalPages.'</total_pages><items_count>'.$this->mItemsCount.'</items_count><previous_page>'.$previous_page.'</previous_page><next_page>'.$next_page.'</next_page></params>';
}

public function getGridXML(){
	return '<grid>'.$this->grid.'</grid>';
}

private function countAllRecords(){
	if (!isset($_SESSION['record_count'])){
		$count_query = 'SELECT COUNT(*) FROM product';
	}
	if ($result = $this->mMysqli->query($count_query)){
		$row = $result->fetch_row();
		$_SESSION['record_count'] = $row[0];
		$result->close();
	}
}



private function createSubpageQuery($queryString, $pageNo){
	if ($this->mItemsCount <= ROWS_PER_VIEW){
		$pageNo = 1;
		$this->mTotalPages = 1;
	}
	else{
		$this->mTotalPages = ceil($this->mItemsCount / ROWS_PER_VIEW);
		$start_page = ($pageNo - 1) * ROWS_PER_VIEW;
		$queryString .= ' LIMIT '. $start_page . ','.ROWS_PER_VIEW;
	}
	$this->mReturnedPage = $pageNo;
	return $queryString;
}
}
?>