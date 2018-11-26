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

.fpdate {
	
	font-size:14px;
   	font-family: Raleway, sans-serif;
    font-weight: bold;
	color: #ffffff;
	terxt-aling: center;
	padding: 5px;
	margin-bottom: 4px;
	background-color: #058440;
	text-align: center; -moz-border-radius: 10px; 
border-radius: 10px;  -webkit-box-shadow: 10px 9px 15px -4px rgba(166,163,166,1);
-moz-box-shadow: 10px 9px 15px -4px rgba(166,163,166,1);
box-shadow: 10px 9px 15px -4px rgba(166,163,166,1);
	width: 50px;

	
	
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
		<?php include 'include/_topads_.php';?>
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<select name="searchType" id="searchType" class="form-control" onchange="getEventList()">
						<option value="">Select</option>
						<option value="">Show All</option>
						<option value="Today">Today</option>
						<option value="This Week">This Week</option>
						<option value="Next Week">Next Week</option>
						<option value="This Month">This Month</option>
						<option value="Next Month">Next Month</option>
					</select>
				</div>
				<div class="col-md-6">
					<div id="showCatLink"></div>
					<input type="hidden" id="winWidth" />
				</div>
			</div>
			
			<div class="row" id="results">
			
			</div>
			<div id="loader"><img src="<?=$baseURL;?>/img/loader.gif" width="100px" /></div>
		</div>
		
	</section>
	<script>
	function getEventList(){
		var hostname = location.hostname;
		var currentURL1 = window.location;
		if(currentURL1.search.indexOf("?") != -1){
			sptCurrentURL = window.location.href.split(/[?#]/)[0];
			var currentURL = sptCurrentURL;
		} else {
			var currentURL = currentURL1;
		}
		var searchType = $('#searchType').val();
		
		document.location.href = currentURL + '?searchType='+searchType;
	}
	
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
	<?php include 'include/_footer_.php';?>
