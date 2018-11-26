<?php include 'include/_main_topnav_.php';?>


<script type="text/javascript">
	//<![CDATA[
	(function () {
		try {
			// ############################# 
			// YOU CAN PUT HERE PUBLISHER ID
			// ############################# 
			var publisherid = "7";
			
			var serverName = location.hostname;
			if(serverName.toUpperCase() == 'LOCALHOST'){
				var baseWidgetUrl = "http://localhost/methuenonline.com/widgets/jspublisherevent.php";
			} else {
				var baseWidgetUrl = "http://www.gooddayevents.com/widgets/jspublisherevent.php";
			}
			var url = window.location.pathname;
			var fileName = url.substring(url.lastIndexOf('/')+1);
			//var fileName = "publisher-js.html";
			
			var locationSearch = document.location.search || "";
			//alert(locationSearch);
			var querystring = locationSearch.substr(1).split("&")
				.filter(function (part) { return part.length > 0; })
				.map(function (part) { var parts = part.split("="); return { name: parts[0], value: parts.slice(1).join("=") }; })
				.reduce(function (obj, part) { obj[part.name] = (obj[part.name] || []).concat(part.value); return obj; }, {});

			var page = parseInt(querystring["page"], 10);
			var date = "";
			//alert(date);
			//alert(parseInt(querystring["date"]));

			if (locationSearch != '') {
				date = querystring["date"];
			}
			
			if (isNaN(page) || page < 1) {
				page = "";
			}
			
			var widgetUrl = baseWidgetUrl
				+ "?publisherid="+ publisherid
				+ "&page="+ page
				+ "&fileName="+ fileName;
			//alert(widgetUrl);
			document.write('<' + 'script language="JavaScript" src="' + widgetUrl + '">' + '<' + '/script' + '>');
		} catch (error) {
			var errorMessage = "Could not load the events. There was an error: " + error;

			alert(errorMessage);
		}
	}());
	//>
</script>