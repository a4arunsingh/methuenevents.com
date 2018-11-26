<?php
session_start();
error_reporting(-1);
include 'lib/_baseURL_.php';
//include 'lib/_appFunction_.php';
include 'lib/Connection.php';
include 'lib/DBQuery.php';

$connection = new Connection();
$dbAccess = new DBQuery();

// date_default_timezone_set("Asia/Kolkata");
date_default_timezone_set("America/New_York");
$todayDate = date('Y-m-d');
$currYear = date('Y');


$totRec = '';

$zoneid = '';
$venue_city = '';

$page=0;

function getStartAndEndDate($week, $year) {
	$dto = new DateTime();
	$dto->setISODate($year, $week);
	$ret['week_start'] = $dto->format('Y-m-d');
	$dto->modify('+6 days');
	$ret['week_end'] = $dto->format('Y-m-d');
	return $ret;
}

if(!empty($_POST["page"])){
	$perPage = 12;
	$page = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	// throw HTTP error if page number is not valid
	if(!is_numeric($page)){
		header('HTTP/1.1 500 Invalid page number!');
		exit();
	}
} /* else {
	$perPage = 12;
	$page = 1;
} */

//get current starting point of records
//$start = (($page-1) * $perPage);

// PAGINATION
if($page){ 
	$start = ($page - 1) * $perPage; //first item to display on this page
	$sno = $start+1;
} else {
	$start = 0;
	$sno = 1;
}
/* Setup page vars for display. */
if ($page == 0) $page = 1; //if no page var is given, default to 1.	
// PAGINATION
$groupName = 'MAIN-Calendar';
$searchStr = '';
if(empty($_SESSION['searchType'])){
	$searchStr = " AND (DATE(eventdate) >= '".$todayDate."')";
} else {
	$searchType = $_SESSION['searchType'];
	if(strtoupper($searchType) == 'TODAY'){
		$searchStr = " AND (DATE(eventdate) = '".$todayDate."')";
	} else if(strtoupper($searchType) == 'THIS WEEK'){
		$thisWeek = date('W');
		$arrWeek = getStartAndEndDate($thisWeek, $currYear);
		$weekStartDate = $arrWeek['week_start'];
		if(strtotime($weekStartDate) < strtotime($todayDate)){
			$weekStartDate = $todayDate;
		}
		$weekEndDate = $arrWeek['week_end'];
		$searchStr = " AND (DATE(eventdate) >= '".$weekStartDate."' AND DATE(eventdate) <= '".$weekEndDate."')";
	} else if(strtoupper($searchType) == 'NEXT WEEK'){
		$nextWeek = date('W') + 1;
		$arrWeek = getStartAndEndDate($nextWeek, $currYear);
		$weekStartDate = $arrWeek['week_start'];
		$weekEndDate = $arrWeek['week_end'];
		$searchStr = " AND (DATE(eventdate) >= '".$weekStartDate."' AND DATE(eventdate) <= '".$weekEndDate."')";
	} else if(strtoupper($searchType) == 'THIS MONTH'){
		$thisMonth = date('m');
		$searchStr = " AND (DATE(eventdate) >= '".$todayDate."') AND (MONTH(eventdate) = '".$thisMonth."' AND YEAR(eventdate) = '".$currYear."')";
	} else if(strtoupper($searchType) == 'NEXT MONTH'){
		$nextMonth = date('m' ,strtotime('first day of +1 month'));
		if($nextMonth == 1){
			$currYear = date('Y', strtotime('+1 year'));
		}
		$searchStr = " AND (DATE(eventdate) >= '".$todayDate."') AND (MONTH(eventdate) = '".$nextMonth."' AND YEAR(eventdate) = '".$currYear."')";
	}
}

$mySQL = "";
$mySQL = "SELECT 
	articleid
	, headline
	, DATE_FORMAT(eventdate, '%a, %b %D %Y') AS eventDateTime
	, DATE_FORMAT(eventdate, '%d') AS eventDate
	, DATE_FORMAT(eventdate, '%b') AS eventMonth
	, eventStartTime
	, (CASE WHEN endTimeHide = 'CHECKED' THEN '' ELSE CONCAT(' - ', eventEndTime) END) AS eventEndTime
	, venue_address
	, venue_city
	, venue_state
	, venue_location
	, zoneid
	, friendlyURL
	, (SELECT CONCAT('".$baseURL."/promos/articlefiles/', filename) AS image FROM articlefiles WHERE filetype = 'image' AND articleid = articles.articleid LIMIT 1) AS articleImage
FROM articles WHERE status = '1' AND status <> '4'";
//$mySQL .= " AND (DATE(eventdate) >= '".$todayDate."')";
$mySQL .= $searchStr;
$mySQL .= " AND articleid IN (SELECT articleid FROM `articlegroup` WHERE idgroup IN (SELECT idgroup FROM `groups` WHERE groupName = '".$groupName."'))";

if(!empty($_SESSION['zoneid'])){
	$zoneid = $_SESSION['zoneid'];
	//$mySQL .= " AND zoneid = '".$_SESSION['zoneid']."'";
	$mySQL .= " AND articleid IN (SELECT articleid FROM articlezone WHERE zoneid = '".$_SESSION['zoneid']."')";
}
if(!empty($_SESSION['venue_city'])){
	$venue_city = $_SESSION['venue_city'];
	$mySQL .= " AND venue_city = '".$_SESSION['venue_city']."'";
}

if (strtolower($domainname) != 'gooddayevents.com'){
//	$mySQL .= " AND domainname = '".$domainname."'";
}
$mySQL .= " ORDER BY DATE_FORMAT(eventDate, '%Y %m %d'), eventStartTime ASC";
//$mySQL .= " ORDER BY DATE(enddate)";
$mySQL .= " LIMIT " . $start . "," . $perPage;
//echo $mySQL .'<hr>';
//exit;
$rsTempRec = $dbAccess->selectData($mySQL);
//echo COUNT($rsTemp);

$myEventData = '';
if(!empty($rsTempRec)) {
	foreach($rsTempRec AS $rsTempRecVal){
		//$myEventData .= '<div  class="well">' .$rsTempVal["description"] . '</div>';
		
		$myEventData .= '<div class="col-md-4 col-sm-6 events-item" id="event_'.$rsTempRecVal["articleid"].'">';
		//$myEventData .= '<a class="events-link" data-toggle="modal" href="#eventsModal1">';
		$myEventData .= '<a class="events-link" href="'.$baseURL.'/event-detail/'.$rsTempRecVal["friendlyURL"].'">';
			$myEventData .= '<div class="events-hover">';
				$myEventData .= '<div class="events-hover-content">';
					$myEventData .= '<i class="fa fa-plus fa-3x"></i>';
				$myEventData .= '</div>';
			$myEventData .= '</div>';
		$myEventData .= '<hr class="style1">';
			$myEventData .= '<img class="img-fluid event-image-size" src="'.$rsTempRecVal["articleImage"].'" alt="'.ucwords($rsTempRecVal["headline"]).'">';
		$myEventData .= '</a>';
		$myEventData .= '<div class="events-caption">';
			$myEventData .= '<div class="row">';
				$myEventData .= '<div class="col-md-2 col-sm-4">';
					$myEventData .= '<div class="fpdate">'.($rsTempRecVal["eventDate"]) . ' ' . ucwords($rsTempRecVal["eventMonth"]).'</div>';
				$myEventData .= '</div>';
				$myEventData .= '<div class="col-md-10 col-sm-8">';
					$myEventData .= '<p class="text-muted"><h5>'.ucwords($rsTempRecVal["headline"]).'</h5></p>';
					$myEventData .= '<div class="text-black">';
						$myEventData .= '<small>';
							//$myEventData .= '<strong>Time:</strong> '.ucwords($rsTempRecVal["eventStartTime"] . $rsTempRecVal["eventYear"]).' | ';
							$myEventData .= '<strong>@</strong> '.ucwords($rsTempRecVal["venue_location"]);
						$myEventData .= '</small>';
					$myEventData .= '</div>';
				$myEventData .= '</div>';
			$myEventData .= '</div>';
		$myEventData .= '</div>';
	$myEventData .= '</div>'; 
	//echo $myEventData;
	}
	//$myEventData .= 'Page No.:' . $page;
	$myEventData .= '<div class="col-md-12 col-sm-6 events-item text-center"><input type="button" name="showmore" id="showmore-'.($page+1).'" value="Show More Events" class="btn btn-default showmore" onclick="loadCoupons('.($page+1).')" /></div>';
}
echo $myEventData;
?>