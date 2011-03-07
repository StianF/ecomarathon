<?PHP include 'db.php';
$_SESSION[config] = mysql_fetch_assoc(mysql_query('SELECT * FROM config'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  	<head>
    	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    	<title>DNV Fuel Fighter 2.0</title>
		<!-- Use Compatibility mode in IE -->
		<!--[if IE]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]--> 
		  
		<!--<link rel="stylesheet" type="text/css" href="jquery.jqplot.css" /> -->
		<link rel="stylesheet" type="text/css" href="style.css" />	
		
		<!-- BEGIN: load jquery --> 
			<script language="javascript" type="text/javascript" src="jquery-1.4.2.js"></script> 
		<!-- END: load jquery --> 

		<!-- BEGIN: stopwatch -->
			<script language="javascript" type="text/javascript" src="stopwatch.js"></script> 
		<!-- END: stopwatch -->		
	
		<!-- BEGIN: prefetch values-->
			<script language="javascript" type="text/javascript" src="<?PHP echo $_SESSION[config][adress_for_data];?>"></script> 
		<!-- END: prefetch values -->	

		<!--Gauges (Fucker opp jqBarGraph)-->
		<script type="text/javascript" src="bindows_gauges.js"></script>

		<!-- BEGIN: highcharts -->
			<script language="javascript" type="text/javascript" src="highcharts.js"></script>
		<!-- END: highcharts -->

		<!--Google maps-->    		
		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAYaog4dzwxoybW9_peuyRMBSz_w1ruw6ENA68gLhXIho5vEdS3BRNfc7XAwpRRJ-YyizO_obSYWjUAw" type="text/javascript"></script>
	    <script type="text/javascript">
		var map;
		var baseIcon;
		var marker;
		var icon;
    		function initialize() {
			if (GBrowserIsCompatible()) {
				map = new GMap2(document.getElementById("map_canvas"));
				map.setCenter(new GLatLng(51.5322, 13.9298), 15);
				map.setMapType(G_SATELLITE_MAP);
				map.setUIToDefault();

				// Create a base icon for all of our markers that specifies the
				// shadow, icon dimensions, etc.
				baseIcon = new GIcon(G_DEFAULT_ICON);
				baseIcon.shadow = "http://www.google.com/mapfiles/shadow50.png";
				baseIcon.iconSize = new GSize(20, 34);
				baseIcon.shadowSize = new GSize(37, 34);
				baseIcon.iconAnchor = new GPoint(9, 34);
				baseIcon.infoWindowAnchor = new GPoint(9, 2);
					
				icon = new GIcon(baseIcon);
				icon.image = "car.png";
			}
		}
		function setCarPos(latitude, longitude){
			if(marker != undefined){
				map.removeOverlay(marker);
			}
			//var bounds = map.getBounds();
			//var southWest = bounds.getSouthWest();
			//var northEast = bounds.getNorthEast();
			//var lngSpan = northEast.lng() - southWest.lng();
			//var latSpan = northEast.lat() - southWest.lat();
			//var latlng = new GLatLng(southWest.lat() + latSpan * Math.random(), southWest.lng() + lngSpan * Math.random());
			var latlng = new GLatLng(latitude, longitude);
		
			// Set up our GMarkerOptions object
			markerOptions = { icon:icon };
			marker = new GMarker(latlng, markerOptions);
			if(typeof map != "undefined" ){
				map.addOverlay(marker);
			}
		}	
    	</script>
	<script language="javascript" type="text/javascript" src="values2.php"></script> 
  	</head>
	<body onload="initialize()" onunload="GUnload()">
		<div style="width:1000px;center;  margin-left: auto ;margin-right: auto ;">
			<div style="text-align:center;">
				<img src="DNV.jpg" height="100px"><h1>Shell Eco Marathon 2011</h1>
			</div>
			<hr>
			<div id="content">
				
				<div id="gauges" style="float:left;">
					<div>
						<div style="float:left;">
							<div style="text-align:center;">Speed:</div>
							
							<div id="speed" style="width:150px;height:150px;"></div>
						</div>
						<div style="float:right;">
							<div style="text-align:center;">Avg Speed:</div>
							<div id="avgspeed" style="width: 150px; height: 150px"></div>
						</div>
					</div>
					<br />
					<div>
						<div style="float:left;">
							<div style="text-align:center;">Calculated Avg speed:</div>
							<div id="calcavgspeed" style="width: 150px; height: 150px"></div>
						</div>
						<div style="float:right;">
							<div style="text-align:center;">Hydrogen pressure:</div>
							<div id="hydrogenpress" style="width: 150px; height: 150px"></div>
						</div>
					</div>
				</div>
				
				<div style="float:right;">			
					<div id="map_canvas" style="width: 500px; height: 400px"></div>
					<br />
					<div id="chart1" style="margin-top:20px; margin-left:20px; width:500px; height:300px;"></div> 
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
			var chart;
			var speed = bindows.loadGaugeIntoDiv("gauge.xml", "speed");
			var avgspeed = bindows.loadGaugeIntoDiv("gauge.xml", "avgspeed");
			var calcavgspeed = bindows.loadGaugeIntoDiv("gauge.xml", "calcavgspeed");
			var hydrogenpress = bindows.loadGaugeIntoDiv("gauge.xml", "hydrogenpress");

			var series = [{ name: 'Cell',data: [0]}];
			$(document).ready(
				function(){
					chart = new Highcharts.Chart({
						chart: {
							renderTo: 'chart1',
							defaultSeriesType: 'column'
						},
						title: {
							text: 'Voltage'
						},
						xAxis: {
							categories: ['1', '2', '3', '4', '5', '6', '7', '8','9','10'],
							title: {
								text: null
							}
						},
						legend: {
							enabled: false
						},
						yAxis: {
							min: 0,
							title: {
								text: 'V',
								align: 'high'
							}
						},
						tooltip: {
							formatter: function() {
								return ''+
									 this.series.name +' ' + this.x + ': '+ this.y +' V';
							}
						},
						plotOptions: {
							bar: {
								dataLabels: {
									enabled: true
								}
							}
						},
						
						credits: {
							enabled: false
						},
							series: series
					});
				}
			);
			//<!--Get values-->
			$(function () {
				updateValues();
			});
			function updateValues() {
				$.getScript('<?PHP echo $_SESSION[config][adress_for_data];?>', function(){updateUI();});
			}
			function updateUI(){
				avgspeed.needle.setValue(values[0]);
				avgspeed.label.setText(values[0]);
				calcavgspeed.needle.setValue(values[1]);
				calcavgspeed.label.setText(values[1]);
				hydrogenpress.needle.setValue(values[2]);
				hydrogenpress.label.setText(values[2]);
				speed.needle.setValue(values[2]);
				speed.label.setText(values[2]);
				chart.series[0].setData(series, true);
				setCarPos(pos[index][0], pos[index][1]);
				setTimeout(updateValues, 2000);
			}
			
		</script>
  	</body>
</html>
