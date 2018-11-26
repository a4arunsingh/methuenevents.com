<?php
session_start();
error_reporting(-1);
include 'lib/_baseURL_.php';
//include 'lib/_appFunction_.php';
include 'lib/Connection.php';
include 'lib/DBQuery.php';

$connection = new Connection();
$dbAccess = new DBQuery();

$todayDate = date('Y-m-d');

$totRec = '';

$zoneid = '';
$venue_city = '';

if(!empty($_POST["page"])){
	$perPage = 12;
	$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	// throw HTTP error if page number is not valid
	if(!is_numeric($page_number)){
		header('HTTP/1.1 500 Invalid page number!');
		exit();
	}
} else {
	$perPage = 12;
	$page_number = 1;
}

//get current starting point of records
$start = (($page_number-1) * $perPage);
			
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
	, zoneid
	, friendlyURL
	, (SELECT CONCAT('".$baseURL."/promos/articlefiles/', filename) AS image FROM articlefiles WHERE filetype = 'image' AND articleid = articles.articleid LIMIT 1) AS articleImage
FROM articles WHERE status = '1' AND status <> '4'";
$mySQL .= " AND (DATE(eventdate) >= '".$todayDate."')";
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
					$myEventData .= '<div class="text-info"><h5>'.($rsTempRecVal["eventDate"]) . ' ' . strtoupper($rsTempRecVal["eventMonth"]).'</h5></div>';
				$myEventData .= '</div>';
				$myEventData .= '<div class="col-md-10 col-sm-4">';
					$myEventData .= '<p class="text-muted"><h5>'.ucwords($rsTempRecVal["headline"]).'</h5></p>';
					$myEventData .= '<div class="text-black">';
						$myEventData .= '<small>';
							//$myEventData .= '<strong>Time:</strong> '.ucwords($rsTempRecVal["eventStartTime"] . $rsTempRecVal["eventEndTime"]).' | ';
							$myEventData .= '<strong>Location:</strong> '.ucwords($rsTempRecVal["venue_address"] . ', ' . $rsTempRecVal["venue_city"]) . ', ' . ucwords($rsTempRecVal["venue_state"]);
						$myEventData .= '</small>';
					$myEventData .= '</div>';
				$myEventData .= '</div>';
			$myEventData .= '</div>';
		$myEventData .= '</div>';
	$myEventData .= '</div>'; 
	//echo $myEventData;
	}
	//$myEventData .= 'Page No.:' . $page;
}
echo $myEventData;
?>