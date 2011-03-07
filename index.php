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
			<script language="javascript" type="text/javascript" src="jquery-1.4.2.min.js"></script> 
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
		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAYaog4dzwxoybW9_peuyRMBTmxJAMRai9ImD_LhpYkYI0uOtUXhRElJmT8ZyykJlgkzQYdkGDSVAUgg" type="text/javascript"></script>
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
			if(typeof marker != "undefined" && typeof map != "undefined"){
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
  	</head>
	<body onload="initialize()" onunload="GUnload()">
		<div style="width:1250px;center;  margin-left: auto ;margin-right: auto ;">
			<div style="text-align:center;">
				<img src="DNV.jpg" height="100px"><h1>Shell Eco Marathon 2011</h1>
				<form name="clock"><input type="text" name="stwa" value="00 : 00 : 00"><input type="button" name="theButton" onClick="stopwatchButton(this.value);" value="Start"><input type="button" onClick="stopwatchButton(this.value);reset();" value="Reset"></form>
			</div>
			<hr>
			<div style="float:top;font-size:75%">
			<input type="button" onClick="showhide('cellvolt');" id="cellbut" value="Cell Voltage">
			<div style="display:none;" id="cellvolt">
				<table>
					<tr>
				<?PHP
					for($i = 0; $i < 46; $i++){
						echo "<td>Cell ".$i."</td>";
					}
					echo "</tr><tr>";
					for($i = 0; $i < 46; $i++){
						echo "<td onclick=\"window.open('http://81.167.78.33/eco/stat.php?type=0&n=".$i."');\" style=\"cursor: pointer\" id=\"cell".$i."\"></td>";
					}
				?>
					</tr>
				</table>
			</div>
			<input type="button" onClick="showhide('temperature');" id="temperaturebut" value="Temperature">
			<div style="display:none;" id="temperature">
				<table>
					<tr>
				<?PHP
					for($i = 0; $i < 12; $i++){
						echo "<td>Sensor ".$i."</td>";
					}
					echo "</tr><tr>";
					for($i = 0; $i < 12; $i++){
						echo "<td onclick=\"window.open('http://81.167.78.33/eco/stat.php?type=2&n=".$i."');\" style=\"cursor: pointer\" id=\"tempsens".$i."\"></td>";
					}
				?>
					</tr>
				</table>
			</div>
			<input type="button" onClick="showhide('outputv');" id="outputbut" value="Output Voltage">
			<div style="display:none;" id="outputv">
				<table>
					<tr>
				<?PHP
					for($i = 0; $i < 5; $i++){
						echo "<td>Output ".$i."</td>";
					}
					echo "</tr><tr>";
					for($i = 0; $i < 5; $i++){
						echo "<td onclick=\"window.open('http://81.167.78.33/eco/stat.php?type=4&n=".$i."');\" style=\"cursor: pointer\" id=\"outputvo".$i."\"></td>";
					}
				?>
					</tr>
				</table>
			</div>
			<input type="button" onClick="showhide('pressure');" id="pressurebut" value="Pressure">
			<div style="display:none;" id="pressure">
				<table>
					<tr>
				<?PHP
					for($i = 0; $i < 3; $i++){
						echo "<td>Pressure ".$i."</td>";
					}
					echo "</tr><tr>";
					for($i = 0; $i < 3; $i++){
						echo "<td onclick=\"window.open('http://81.167.78.33/eco/stat.php?type=3&n=".$i."');\" style=\"cursor: pointer\" id=\"pressures".$i."\"></td>";
					}
				?>
					</tr>
				</table>
			</div>
			</div>
			<div id="content">
				<div style="float:left;margin-top:5px;">
					<table border="1" width="500px">
						<tr><th>Lap #</th><th>Actual Time</th><th>Planned time</th><th>Diff</th></tr>
						<?PHP $times = mysql_query("SELECT * FROM laps");
						while($lap = mysql_fetch_assoc($times)){
							echo "<tr>";
							echo "<td>".$lap[id]."</td>";
							echo "<td id=\"lap".$lap[id]."\">".floor($lap[time]/60).":".str_pad($lap[time]%60, 2, "0", STR_PAD_LEFT)."</td>";
							echo "<td>".floor($lap[planned_time]/60).":".str_pad($lap[planned_time]%60, 2, "0", STR_PAD_LEFT)."</td>";
							$diff = $lap[time]-$lap[planned_time];
							echo "<td id=\"lapdiff".$lap[id]."\">".floor($diff/60).":".str_pad(abs($diff)%60, 2, "0", STR_PAD_LEFT)."</td>";
							echo "</tr>";
						}
						?>
					</table>
					<table>
						<tr><td>Avg speed</td><td id="avgspeed"></td></tr>
					</table>
				</div>
				<div style="float:right;">			
					<div id="map_canvas" style="width: 500px; height: 400px"></div>
				</div>
			<!--	<div style="width:1280px;">
					<div class="chart" style="visibility:hidden">
						<div id="cell_voltage" style="visibility:hidden" ></div> 
					</div>
					<div class="chart">
						<div id="temperature" ></div>
					</div>
				<div>-->
			</div>
		</div>
			
		<script type="text/javascript">
			var pos;
			var visibleChart = 0;
			var visibleCount = 0;

			function showhide(div) {

				if($("#"+div).css("display") == "none"){
					$("#"+div).css("display", "block");
				}else{
					$("#"+div).css("display", "none");
				}
			}
			//<!--Get values-->
			$(function () {
				updateValues();
			});
			function updateValues() {
				$.getScript('<?PHP echo $_SESSION[config][adress_for_data];?>', function(){updateUI();});
			}
			function updateUI(){
				found = false;
				for(i = 0; i < 46; i++){
					$("#cell"+i).text(cell_voltage[i]+" V");
					if(cell_voltage[i] == 0.0 || cell_voltage[i] > 1.8){
						$("#cell"+i).css("color", "red");
						found = true;
					}else{
						$("#cell"+i).css("color", "green");
					}
				}
				if(found){
					$("#cellbut").css("background-color", "red");
				}else{
					$("#cellbut").css("background-color", "");
				}
				found = false;
				for(i = 0; i < 12; i++){
					$("#tempsens"+i).text(temperature[i]+" C");
					if(temperature[i] == 0.0 || temperature[i] > 40){
						$("#tempsens"+i).css("color", "red");
						found = true;
					}else{
						$("#tempsens"+i).css("color", "green");
					}
				}
				if(found){
					$("#temperaturebut").css("background-color", "red");
				}else{
					$("#temperaturebut").css("background-color", "");
				}
				found = false;
				for(i = 0; i < 5; i++){
					$("#outputvo"+i).text(fuelcell_out[i]+" V");
					if(fuelcell_out[i] == 0.0 || fuelcell_out[i] > 18){
						$("#outputvo"+i).css("color", "red");
						found = true;
					}else{
						$("#outputvo"+i).css("color", "green");
					}
				}
				if(found){
					$("#outputbut").css("background-color", "red");
				}else{
					$("#outputbut").css("background-color", "");
				}
				found = false;
				for(i = 0; i < 3; i++){
					$("#pressures"+i).text(pressure[i]+" Pa");
					if(pressure[i] == 0.0 || pressure[i] > 18){
						$("#pressures"+i).css("color", "red");
						found = true;
					}else{
						$("#pressures"+i).css("color", "green");
					}
				}
				if(found){
					$("#pressurebut").css("background-color", "red");
				}else{
					$("#pressurebut").css("background-color", "");
				}
				setCarPos(pos[index][0], pos[index][1]);
				setTimeout(updateValues, 2000);
			}
			function stopwatchButton(value){
				if(value == 'Start'){
					$.ajax({url: "http://81.167.78.33/eco/config.php?action=start_clock"});
					stopwatch(value);
				}else if(value == 'Stop '){
					$.ajax({url: "http://81.167.78.33/eco/config.php?action=stop_clock"});
					stopwatch(value);		
				}else if(value == 'Reset'){
					$.ajax({url: "http://81.167.78.33/eco/config.php?action=reset_clock"});
					resetIt();
				}
			}
		</script>
  	</body>
</html>
