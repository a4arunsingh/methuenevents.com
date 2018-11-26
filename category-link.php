<?php
session_start();
error_reporting(0);
include 'lib/_baseURL_.php';
//include 'lib/_appFunction_.php';
include 'lib/Connection.php';
include 'lib/DBQuery.php';

$connection = new Connection();
$dbAccess = new DBQuery();

date_default_timezone_set("America/New_York");
$todayDate = date('Y-m-d');

$perPage = 3;
$page = 1;
$totRec = '';

$zoneid = '';
$venue_city = '';

$start = $_POST["row"];
$winWidth = $_POST["winWidth"];

//echo 'winWidth = ' . $winWidth;
//echo 'ZONE ID = ' . $_SESSION['zoneid'];
if(empty($_SESSION['zoneid'])){
	$catCss = 'cat-border-active';
} else {
	$catCss = 'cat-border';
}

$mySQL = "";
$mySQL = "SELECT zoneid, zonename, friendlyURL FROM";
$mySQL1 = " zones WHERE categoryFor = '1' ORDER BY zonename";
//$mySQL2 = " LIMIT " . $start . "," . $limit;
$mySQLQry = $mySQL . $mySQL1;	// . $mySQL2;
//echo $mySQLQry .'<hr>';
$rsTemp = $dbAccess->selectData($mySQLQry);

$myCatLink = '';
if (!empty($rsTemp)){
	/*
	if($winWidth > '960'){
?>
		<div class="row text-center divCat" id="divCat">
			<div class="col-md-21 text-center">
				<a href="<?=$baseURL;?>" class="text-danger"><div class="linkCat <?=$catCss;?>">All</div></a>
			</div>
			<?php
			foreach($rsTemp AS $rsTempVal){
				if(!empty($_SESSION['zoneid']) AND ($_SESSION['zoneid'] == $rsTempVal['zoneid'])){
					$catCss = 'cat-border-active';
				} else {
					$catCss = 'cat-border';
				}
			?>
				<div class="col-md-21 text-center">
					<a href="<?=$baseURL;?>/event-list/<?=$rsTempVal['friendlyURL'];?>" class="text-danger"><div class="linkCat <?=$catCss;?>"><?=$rsTempVal['zonename'];?></div></a>
				</div>
			<?php
			}
			?>
		</div>
<?php
	} else {*/
		$myCatLink .= '<select name="eventCat" id="ddlCat" class="form-control ddlCat1" onchange="document.location.href=this.value">';
			$myCatLink .= '<option value="'.$baseURL.'">All Category</option>';
			foreach($rsTemp AS $rsTempVal){
				if(!empty($_SESSION['zoneid']) AND ($_SESSION['zoneid'] == $rsTempVal['zoneid'])){
					$selectVal = 'SELECTED';
				} else {
					$selectVal = '';
				}
				$myCatLink .= '<option value="'.$baseURL.'/event-list/'.$rsTempVal['friendlyURL'].'" '.$selectVal.'>'.$rsTempVal['zonename'].'</option>';
			}
		$myCatLink .= '</select>';
	//}
	echo $myCatLink;
}

?>