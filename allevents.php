<?php
session_start();
error_reporting(-1);

//echo strtolower(basename($_SERVER["PHP_SELF"])) . '<br>';
//echo ($_SERVER["REQUEST_URI"]) . '<br>';

$chkURL = explode("/", strtolower($_SERVER["REQUEST_URI"]));
//echo $chkURL[1] . '<br>';
//if($chkURL[1] == 'event-detail' OR $chkURL[2] == 'event-detail'){
if(strtolower($chkURL[1]) == 'event-detail'
	//OR strtolower($chkURL[2]) == 'event-detail'
	OR strtolower($chkURL[1]) == 'pgs'
	OR strtolower($chkURL[1]) == 'pgs'
	OR strtolower($chkURL[1]) == 'submit-an-event'
	OR strtolower($chkURL[1]) == 'business-post-events'
	//OR strtolower($chkURL[2]) == 'business-post-events'
	OR strtolower($chkURL[1]) == 'contact-us'
){
	$urlExt = "../";
} else {
	$urlExt = '';
}
//echo $baseURLphp = $urlExt.'lib/_baseURL_.php';

include ($urlExt.'lib/_baseURL_.php');
include ($urlExt.'lib/_appFunction_.php');
//include 'include/_chksession_.php';
include ($urlExt.'lib/Connection.php');
include ($urlExt.'lib/Fileupload.php');
include ($urlExt.'lib/DBQuery.php');
include ($urlExt.'lib/Helpers.php');
include ($urlExt.'lib/ReturnMsg.php');
include ($urlExt.'lib/SentEmailSMS.php');

$connection = new Connection();
$helper = new Helpers();
$FileUpload = new Fileupload();
$dbAccess = new DBQuery();
$LoadMsg = new ReturnMsg();
$sendMailMsg = new SentEmailSMS();
$appFunctions = new appFunction();

$todayDate = date('Y-m-d');
$errMsg = '';

//echo 'zoneid = ' . $_SESSION['zoneid'] .'<br>';

if(!empty($_POST['venue_city'])) {
	unset($_SESSION['zoneid']);
	$_SESSION['venue_city'] = $_POST["venue_city"];
	$venue_city = $_SESSION['venue_city'];
	//echo 'venue_city = ' . $venue_city .'<br>';
	//exit;
}

//echo basename($_SERVER["REQUEST_URI"]) . '<br>';
//echo explode("/", basename($_SERVER["REQUEST_URI"]));
$friendlyURLZone = '';
$friendlyURLZone = basename($_SERVER["REQUEST_URI"]);
if(!empty($friendlyURLZone)){
	//echo 'friendlyURLZone = ' . $friendlyURLZone . '<br>';
	//$rsTemp = $dbAccess->getDetail('zones', 'zoneid', 'friendlyURL', $friendlyURLZone);
	
	$mySQL = "";
	$mySQL = "SELECT zoneid FROM zones WHERE categoryFor = '1' AND friendlyURL = '".$appFunctions->validSQL($friendlyURLZone,"")."' ORDER BY zonename";
	//echo $mySQL .'<hr>';
	$rsTemp = $dbAccess->selectSingleStmt($mySQL);
	$_SESSION['zoneid'] = $rsTemp['zoneid'];
	$zoneid = $_SESSION['zoneid'];
	//echo 'zoneid = ' . $zoneid . '<br>';
} else {
	unset($_SESSION['zoneid']);
}

$idconfigsite = '';
//$domainname = '';
$headtitle = '';
$copyrighttitle = '';
$companylogo = '';
$companylogo_Old = '';
$footerlogo = '';
$footerlogo_Old = '';
$emailinfo = '';
$emailcontact = '';
$emailsupport = '';
$emailadmin = '';

$mySQL = "";
$mySQL = "SELECT `idconfigsite`, `domainname`, `headtitle`, `copyrighttitle`, `companylogo`, `footerlogo`, `emailinfo`, `emailcontact`, `emailsupport`, `emailadmin`, `isActive` FROM `configsite` WHERE domainname = '".$appFunctions->validSQL($domainname,"")."'";
//echo '<br><br><br>' . $mySQL .'<br>';
$rsTemp = $dbAccess->selectSingleStmt($mySQL);

if(!empty($rsTemp)){
	$idconfigsite = $rsTemp['idconfigsite'];
	$domainname = $rsTemp['domainname'];
	$headtitle = $rsTemp['headtitle'];
	$copyrighttitle = $rsTemp['copyrighttitle'];
	$companylogo = $rsTemp['companylogo'];
	$footerlogo = $rsTemp['footerlogo'];
	$emailinfo = $rsTemp['emailinfo'];
	$emailcontact = $rsTemp['emailcontact'];
	$emailsupport = $rsTemp['emailsupport'];
	$emailadmin = $rsTemp['emailadmin'];
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no">
		
   <?php include ($urlExt.'include/_metadetail_.php');?>
    <!-- Bootstrap core CSS -->
    <link href="<?=$baseURL;?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template -->
    <link href="<?=$baseURL;?>/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
		<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
    <!-- Custom styles for this template -->
    <link href="<?=$baseURL;?>/css/agency.min.css" rel="stylesheet">
	
	<link href="<?=$baseURL;?>/css/full-slider.css" rel="stylesheet">
	
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="data-load/script/load_data.js"></script>-->
    <script>
	function getCityEvents(venue_city){
		//alert(venue_city);
		$('#selectedLocation').html(venue_city);
		$('#venue_city').val(venue_city);
		$('#frmCity').submit();
	}
	</script>
		
	
	<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.9/jquery-ui.js" type="text/javascript"></script>
	<link href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.9/themes/start/jquery-ui.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
    $(function () {
        $("#dialog").dialog({
            title: "Select Location",
            buttons: {
                Close: function () {
                    $(this).dialog('close');
                }
            }
        });
		
		//$("#dialog").dialog();
    });
	</script>-->
	
	<!--<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({ publisher: "9ee8c443-57cf-44ea-8e67-11693a461baf", doNotHash: false, doNotCopy: false, hashAddressBar: false });</script>-->
	
	<script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5b9b4ec7df87bb0011f9f0ff&product=sop' async='async'></script>
</head>
	
	<!-- Portfolio Grid -->
	<style>
	.cat-border{
		font-size:13px;font-family:Arial;border-radius:50px;border:1px solid #000;padding:5px 25px;margin:10px 15px;
	}
	.cat-border-active{
		font-size:13px;font-family:Arial;border-radius:50px;border:1px solid #EF0000;padding:5px 25px;margin:10px 15px;
	}
	hr.style1{
		border-top: 3px solid #e20606;
	}
	
	@media screen and (min-width: 992px) {
		.event-image-size {
			min-width: 349px !important;
			max-height: 182px !important;
		}
		
	}
	
	@media screen and (width:736px) and (height:414px) {
		.event-image-size {
			min-width: 240px !important;
			max-height: 140px !important;
		}
		.h5, h5 {
			font-size: 12px;
		}
	}
	
	@media screen and (width:640px) and (height:360px) {
		.event-image-size {
			min-width: 240px !important;
			max-height: 140px !important;
		}
		.h5, h5 {
			font-size: 12px;
		}
	}
	@media screen and (width:667px) and (height:375px) {
		.event-image-size {
			min-width: 240px !important;
			max-height: 140px !important;
		}
		.h5, h5 {
			font-size: 12px;
		}
	}
	@media screen and (width:823px) and (height:411px) {
		.event-image-size {
			min-width: 240px !important;
			max-height: 140px !important;
		}
		.h5, h5 {
			font-size: 12px;
		}
	}
	.slider1 {
		width: 100%;
		text-align:center;
		
	}
	
	.img-fluid, .img-thumbnail {
		border: 1px solid #000 !important;
	}
	</style>
	<script src='<?=$baseURL;?>/js/jquery-3.0.0.js' type='text/javascript'></script>
	
	<script>
	//var track_page = 1; //track user scroll as page number, right now page number is 1
	//var loading  = false; //prevents multiple loads

	//loadCoupons(track_page); //initial content load

	/* $(window).scroll(function() { //detect page scroll
		if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled to bottom of the page
			track_page++; //page number increment
			loadCoupons(track_page); //load content	
		}
	}); */		
	//Ajax load function
	function loadCoupons(track_page){
		//alert(track_page);
		if(loading == false){
			loading = true;  //set loading flag on
			//$('.loading-info').show(); //show loading animation 
			$('#loader').show(); //show loading animation 
			$.post( '<?=$baseURL;?>/result_data.php', {'page': track_page}, function(data){
				loading = false; //set loading flag off once the content is loaded
				//alert(data);
				//alert(data.length);
				$('.showmore').hide();
				//if(track_page != '1'){
					$('#showmore'+track_page).show();
				//}
				if(data.trim().length == 0){
					//notify user if nothing to load
					$('.loading-info').html("No more records!");
					$('#loader').fadeOut(2000);
					return;
				}
				//$('.post-loader').hide(); //hide loading animation once data is received
				$("#results").append(data); //append data into #results element
				$('#loader').fadeOut(2000);
				
			}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
				//alert(thrownError); //alert with HTTP error
			})
		}
	}
	</script>
	
	<section class="bg-light" id="events">
	
		<div class="container">
			
			<div id="showCatLink"></div>
			<input type="hidden" id="winWidth" />
			<div class="row" id="results">
				
			</div>
			<div id="loader"><img src="<?=$baseURL;?>/img/loader.gif" width="100px" /></div>
		
		</div>
		
	</section>
	<script>
	//alert('WIN WIDTH: ' + $(window).width());
	var eventFired = 0;
	
	if ($(window).width() < 960) {
		//alert('Less than 960');
		$('#winWidth').val($(window).width());
		getCatLink();
	} else {
		//alert('More than 960');
		eventFired = 1;
		$('#winWidth').val($(window).width());
		getCatLink();
	}
	$(window).on('resize', function() {
		//alert(eventFired);
		//if (!eventFired) {
			
			$('#winWidth').val($(window).width());
			
			if ($(window).width() < 960) {
				//alert('Less than 960 resize');
				
				/* $.post("<?= $baseURL; ?>/category-link.php",
				{
					action: 'Remind Me',
					winWidth: $('#winWidth').val()
				},
				function(data, status){
					//alert("Data: " + data + "\nStatus: " + status);
					$("#showCatLink").html(data);
				}); */
				getCatLink();
			} else {
				//alert('More than 960 resize');
				getCatLink();
			}
		//}
	});
	
	function getCatLink(){
		$.post("<?= $baseURL; ?>/category-link.php",
		{
			action: 'Remind Me',
			winWidth: $('#winWidth').val()
		},
		function(data, status){
			//alert("Data: " + data + "\nStatus: " + status);
			$("#showCatLink").html(null);
			$("#showCatLink").html(data);
		});
	}
	</script>
	<script>
	var track_page = 1; //track user scroll as page number, right now page number is 1
	var loading  = false; //prevents multiple loads

	loadCoupons(track_page); //initial content load
	</script>
	
