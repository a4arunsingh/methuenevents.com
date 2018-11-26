	<?php include 'include/_header_.php';?>
	
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
			min-width: 330px !important;
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
	$(document).ready(function(){
		$(window).scroll(function(){
			var position = $(window).scrollTop();
			var bottom = $(document).height() - $(window).height();
			
			//alert('position = ' + position);
			//alert('bottom = ' + bottom);
			
			//if( position == bottom ){
			
			var scroll_pos = $(window).scrollTop();
			var win_height = $(window).height();
			var doc_height = $(document).height();
			
			//if ((scroll_pos + win_height) > (doc_height - 450)) {
			if($(window).scrollTop() + $(window).height() >= $(document).height()) {
				var row = Number($('#row').val());
				var allcount = Number($('#all').val());
				var rowperpage = 6;
				row = row + rowperpage;
				//alert(row);
				if(row <= allcount){
					$('#row').val(row);
					$.ajax({
						url: '<?=$baseURL;?>/result_data.php',
						type: 'post',
						data: {row:row},
						beforeSend: function(){
							$('#loader').show();
						},
						complete: function(){
							$('#loader').fadeOut(2000);			
						},
						success: function(response){
							$(".events-item:last").after(response).show().fadeIn("slow");
						}
					});
				}
			}

		});
	});
	</script>
	
	
	
	<section class="bg-light" id="events">
		<div class="container">
			
			<?php
			/*
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
			if (!empty($rsTemp)){
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
							<!--<a href="javascript:void(0)" onclick="getCatgoryEvents('<?//=$rsTempVal['zoneid'];?>')" class="text-danger"><div class="linkCat <?//=$catCss;?>"><?//=$rsTempVal['zonename'];?></div></a>-->
							<a href="<?=$baseURL;?>/event-list/<?=$rsTempVal['friendlyURL'];?>" class="text-danger"><div class="linkCat <?=$catCss;?>"><?=$rsTempVal['zonename'];?></div></a>
						</div>
					<?php
					}
					?>
				</div>
				<select name="eventCat" id="ddlCat" class="form-control ddlCat">
					<option value="<?=$baseURL;?>">All</option>
					<?php
					foreach($rsTemp AS $rsTempVal){
						if(!empty($_SESSION['zoneid']) AND ($_SESSION['zoneid'] == $rsTempVal['zoneid'])){
							$selectVal = 'SELECTED';
						} else {
							$selectVal = '';
						}
					?>
						<option value="<?=$baseURL;?>/event-list/<?=$rsTempVal['friendlyURL'];?>" <?=$selectVal;?>><?=$rsTempVal['zonename'];?></option>
					<?php
					}
					?>
				</select>
			<?php
			}
			*/
			?>
			<div id="showCatLink"></div>
			<input type="hidden" id="winWidth" />
			<div class="row" id="results1">
				<?php
				$start = 0;
				$perPage = 6;
				
				$mySQL = "";
				$mySQL = "SELECT COUNT(0) AS totRec FROM articles WHERE status <> '4' AND DATE(eventDate) >= '".$todayDate."'";
				if(!empty($_SESSION['zoneid'])){
					$zoneid = $_SESSION['zoneid'];
					//$mySQL .= " AND zoneid = '".$_SESSION['zoneid']."'";
					$mySQL .= " AND articleid IN (SELECT articleid FROM articlezone WHERE zoneid = '".$_SESSION['zoneid']."')";
				}
				//if(!empty($_SESSION['venue_city'])){
				//	$venue_city = $_SESSION['venue_city'];
				//	$mySQL .= " AND venue_city = '".$_SESSION['venue_city']."'";
				//}
				if (strtolower($domainname) != 'gooddayevents.com'){
					$mySQL .= " AND domainname = '".$appFunctions->validSQL($domainname,"")."'";
				}
				//$mySQL .= " ORDER BY DATE_FORMAT(eventDate, '%Y %m %d'), eventStartTime ASC";
				$mySQL .= " ORDER BY DATE(eventDate), eventStartTime ASC";
				$mySQL .= " LIMIT " . $start . "," . $perPage;
				//echo $mySQL .'<hr>';
				$rsTemp = $dbAccess->selectSingleStmt($mySQL);
				$totRec = $rsTemp['totRec'];
				
				$mySQL = "";
				$mySQL = "SELECT 
					articleid
					, headline
					, DATE_FORMAT(eventDate, '%d') AS eventDate
					, DATE_FORMAT(eventDate, '%b') AS eventMonth
					, eventStartTime
					, (CASE WHEN endTimeHide = 'CHECKED' THEN '' ELSE CONCAT(' - ', eventEndTime) END) AS eventEndTime
					, venue_address
					, venue_city
					, venue_location
					, venue_state
					, zoneid
					, friendlyURL
					, (SELECT CONCAT('http://www.', domainname, '/promos/articlefiles/', filename) AS image FROM articlefiles WHERE filetype = 'image' AND articleid = articles.articleid LIMIT 1) AS articleImage
				FROM articles WHERE status <> '4' AND DATE(eventDate) >= '".$todayDate."'";
				if(!empty($_SESSION['zoneid'])){
					$zoneid = $_SESSION['zoneid'];
					//$mySQL .= " AND zoneid = '".$_SESSION['zoneid']."'";
					$mySQL .= " AND articleid IN (SELECT articleid FROM articlezone WHERE zoneid = '".$_SESSION['zoneid']."')";
				}
				//if(!empty($_SESSION['venue_city'])){
				//	$venue_city = $_SESSION['venue_city'];
				//	$mySQL .= " AND venue_city = '".$_SESSION['venue_city']."'";
				//}
				if (strtolower($domainname) != 'gooddayevents.com'){
					$mySQL .= " AND domainname = '".$appFunctions->validSQL($domainname,"")."'";
				}
				$mySQL .= " ORDER BY DATE_FORMAT(eventDate, '%Y %m %d'), eventStartTime ASC";
				$mySQL .= " LIMIT " . $start . "," . $perPage;
				//echo $mySQL .'<hr>';
				$rsTempRec = $dbAccess->selectData($mySQL);
				foreach($rsTempRec AS $rsTempRecVal){
				?>
					<div class="col-md-4 col-sm-6 events-item" id="event_<?=$rsTempRecVal["articleid"];?>">
						
						<a class="events-link" href="/event-detail/<?=$rsTempRecVal["friendlyURL"];?>">
							
							<div class="events-hover">
								<div class="events-hover-content">
									<i class="fa fa-plus fa-3x"></i>
								</div>
							</div>
							<hr class="style1">	
							<img class="img-fluid event-image-size" src="<?=$rsTempRecVal["articleImage"];?>" alt="<?=ucwords($rsTempRecVal["headline"]);?>" />
						</a>
							
						<div class="events-caption">
							<div class="row">
								<div class="col-md-2 col-sm-2">
									<div class="text-info"><h5><?=($rsTempRecVal["eventDate"]);?> <?=strtoupper($rsTempRecVal["eventMonth"]);?></h5></div>
								</div>
								<div class="col-md-10 col-sm-10">
									<p class="text-muted"><h5><?=ucwords($rsTempRecVal["headline"]);?></h5></p>
									<div class="text-black">
										<div style="font-size: 12px; font-family: Raleway, sans-serif; color: #3c3c3c;">
											<?=ucwords($rsTempRecVal["eventStartTime"] . $rsTempRecVal["eventEndTime"]);?><br>
											<?=ucwords($rsTempRecVal["venue_location"]);?><br>
											<?=ucwords($rsTempRecVal["venue_address"] . ', ' . $rsTempRecVal["venue_city"]);?>, <?=ucwords($rsTempRecVal["venue_state"]);?>
										</div>
										
										
									</div>
								</div>
							</div>
						</div>
				
					</div>
				
				<?php
				}
				?>
				<input type="hidden" id="row" value="3" />
				<input type="hidden" id="all" value="<?php echo $totRec; ?>" />
				<?php //include('result_data.php'); ?>
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
	
	<?php include 'include/_footer_.php';?>
