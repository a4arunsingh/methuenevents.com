<?php $page_title ="Display Facebook Page Events on Website"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title><?php echo $page_title; ?></title>
 
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" media="screen">
 
</head>
<body>
     
<?php 
//$fb_page_id = "arun.singh.31392"; 
$fb_page_id = "d7d97680f1994dfaac5737a2b1d855ae"; 
?>

<?php
$year_range = 2;
 
// automatically adjust date range
// human readable years
$since_date = date('Y-01-01', strtotime('-' . $year_range . ' years'));
$until_date = date('Y-01-01', strtotime('+' . $year_range . ' years'));
 
// unix timestamp years
$since_unix_timestamp = strtotime($since_date);
$until_unix_timestamp = strtotime($until_date);
 
// or you can set a fix date range:
// $since_unix_timestamp = strtotime("2012-01-08");
// $until_unix_timestamp = strtotime("2018-06-28");
 
// page access token
//$access_token = "YOUR_ACCESS_TOKEN";
$access_token = "1478952475582475|iPkp-OS_4OdlsrCTQkJ0ieDluno";


$fields="id,name,description,place,timezone,start_time,cover";
 
$json_link = "https://graph.facebook.com/v3.0/{$fb_page_id}/events/attending/?fields={$fields}&access_token={$access_token}&since={$since_unix_timestamp}&until={$until_unix_timestamp}";
 
$json = file_get_contents($json_link);

print_r($json);

$obj = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
 
// for those using PHP version older than 5.4, use this instead:
// $obj = json_decode(preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', $json), true);

?>
<div class="container">
 
	<!-- events will be here -->
	<div class="page-header">
 
	<h3><?php echo $page_title; ?></h3>
		<?php
		echo "<table class='table table-hover table-responsive table-bordered'>";
 
		// count the number of events
		$event_count = count($obj['data']);
 
		for($x=0; $x<$event_count; $x++){
			// facebook page events will be here
			
			// set timezone
			date_default_timezone_set($obj['data'][$x]['timezone']);
			 
			$start_date = date( 'l, F d, Y', strtotime($obj['data'][$x]['start_time']));
			$start_time = date('g:i a', strtotime($obj['data'][$x]['start_time']));
			  
			$pic_big = isset($obj['data'][$x]['cover']['source']) ? $obj['data'][$x]['cover']['source'] : "https://graph.facebook.com/v2.7/{$fb_page_id}/picture?type=large";
			 
			$eid = $obj['data'][$x]['id'];
			$name = $obj['data'][$x]['name'];
			$description = isset($obj['data'][$x]['description']) ? $obj['data'][$x]['description'] : "";
			 
			// place
			$place_name = isset($obj['data'][$x]['place']['name']) ? $obj['data'][$x]['place']['name'] : "";
			$city = isset($obj['data'][$x]['place']['location']['city']) ? $obj['data'][$x]['place']['location']['city'] : "";
			$country = isset($obj['data'][$x]['place']['location']['country']) ? $obj['data'][$x]['place']['location']['country'] : "";
			$zip = isset($obj['data'][$x]['place']['location']['zip']) ? $obj['data'][$x]['place']['location']['zip'] : "";
			 
			$location="";
			 
			if($place_name && $city && $country && $zip){
				$location="{$place_name}, {$city}, {$country}, {$zip}";
			}else{
				$location="Location not set or event data is too old.";
			}
		}
		echo "</table>";
		?>
	</div>
</div>
 
<!-- jQuery library -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
 
<!-- bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
</body>
</html>