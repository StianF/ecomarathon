<?php
	include 'db.php';
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
			<script type="text/javascript" src="jquery-1.4.2.min.js"></script> 
		<!-- END: load jquery --> 
			<script type="text/javascript" src="clock.js"></script>
		<!-- BEGIN: prefetch values-->
			<script type="text/javascript" src="getBloggData.php"></script> 
		<!-- END: prefetch values -->	
		<!--Gauges (Fucker opp jqBarGraph)-->
		<script type="text/javascript" src="bindows_gauges.js"></script>
	
		<!-- BEGIN: highcharts -->
<!--			<script language="javascript" type="text/javascript" src="highcharts.js"></script>-->
		<!-- END: highcharts -->
	<script type="text/javascript">
		function toggleLayer( whichLayer ) {
		  var elem, vis;
			if( document.getElementById ) 
				elem = document.getElementById( whichLayer );
			else if( document.all ) 
				elem = document.all[whichLayer];
			else if( document.layers )
				elem = document.layers[whichLayer];
				vis = elem.style;
			if(vis.display=='' && elem.offsetWidth != undefined && elem.offsetHeight!=undefined)
				vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
				vis.display = (vis.display==''||vis.display=='block')?'none':'block';
			}

		</script>

		<!--Google maps-->    		
		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAYaog4dzwxoybW9_peuyRMBTmxJAMRai9ImD_LhpYkYI0uOtUXhRElJmT8ZyykJlgkzQYdkGDSVAUgg" type="text/javascript"></script>
	    <script type="text/javascript">
		var map;
		var baseIcon;
		var marker;
		var icon;
		var followcar = false;
		var mapmodechanged = false;
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
				baseIcon.iconSize = new GSize(34, 34);
				baseIcon.shadowSize = new GSize(37, 34);
				baseIcon.iconAnchor = new GPoint(9, 34);
				baseIcon.infoWindowAnchor = new GPoint(9, 2);
					
				icon = new GIcon(baseIcon);
				icon.image = "ff-logo-small.png";
			}
		}
		function setCarPos(latitude, longitude){
			if(typeof marker != "undefined" && typeof map != "undefined"){
				map.removeOverlay(marker);
			}
			var latlng = new GLatLng(latitude, longitude);
			// Set up our GMarkerOptions object
			markerOptions = { icon:icon };
			marker = new GMarker(latlng, markerOptions);
			if(typeof map != "undefined" ){
				if(mapmodechanged){
					map.setCenter(new GLatLng(51.5322, 13.9298), 15);
					mapmodechanged = false;
				}
				if(followcar){
					map.setCenter(latlng);
				}
				map.addOverlay(marker);
			}
		}
		function setFollow(){
			followcar = true;
		}
		function unsetFollow(){
			followcar = false;
			mapmodechanged = true;
		}

	</script>
  	</head>
	<body onload="initialize()" onunload="GUnload()">
		<center>
			<div id="message"></div><div id="clock">00 : 00 : 00</div>
		</center>
			<hr>
			<div id="content">
				<div>			
					<div id="map_canvas" style="width: 500px; height: 400px"></div>
					<form>
						Fixed map to track: <input type="radio" name="followcar" onClick="unsetFollow();" checked >
						Follow Car: <input type="radio" name="followcar" onClick="setFollow();">
					</form>
				</div>
				<div>
					<div style="float:left">
					<table border="1" width="300px">
						<tr><th>Lap #</th><th>Time</th><th>Avg speed</th></tr>
						<?PHP $times = mysql_query("SELECT * FROM laps");
							$totaltime = 0;
						while($lap = mysql_fetch_assoc($times)){
							$totaltime += $lap[time];
							echo "<tr>";
							echo "<td>".$lap[id]."</td>";
							echo "<td id=\"lap".$lap[id]."\">".(($lap[time] != "")?floor($lap[time]/60).":".str_pad($lap[time]%60, 2, "0", STR_PAD_LEFT):"")."</td>";
							echo "<td id=\"avglap".$lap[id]."\">".(($lap[time] != 0)?round((3173/$lap[time])*3.6, 2)." km/h":"")."</td>";
							echo "</tr>";
						}
						?>
						<tr><td>Total</td>
							<td id="totaltime"><?PHP echo floor($totaltime/60).":".str_pad($totaltime%60, 2, "0", STR_PAD_LEFT);?></td>
							<?PHP $diff = $totaltime-$totalplanned;?>
							<td id="avgspeed">
							<?PHP 
								if($totaltime != 0){
									$totaldistance = mysql_fetch_assoc(mysql_query("SELECT max(distance) as max FROM realcps WHERE visited = 1"));
									echo round(($totaldistance[max]/$totaltime)*3.6, 2)." km/h";
								}
							?>
							</td>
						</tr>
					</table>
					</div>
					<div style="float:right">
						<div style="text-align:center;">Speed:</div>
						<div id="speed" style="width:150px;height:150px;"></div>
					</div>
				</div>
			</div>
		</div>
			
		<script type="text/javascript">
			var pos;
			var visibleChart = 0;
			var visibleCount = 0;
			var speedg = bindows.loadGaugeIntoDiv("gauge.xml", "speed");

			<?PHP 
			if ($_SESSION[config][time] != "0000-00-00 00:00:00"){
				$time = strtotime("now")-strtotime($_SESSION[config][time]);
				echo "var sec = ".($time%60).";";
				echo "var min = ".(floor($time/60)%60).";";
				echo "var hour = ".floor($time/3600).";";
				if($_SESSION[config][time_status] == 1){
				}
			}else{	
			?>
				$('#message').text('Race stopped');
				$('#clock').text('');
				var sec = 0;
				var min = 0;
				var hour = 0;
			<?PHP
			}
			?>
			stopwatch();
			//<!--Get values-->
			$(function () {
				updateValues();
			});
			function updateValues() {
				$.getScript('getBloggData.php', function(){updateUI();});
			}
			function updateUI(){
				speedg.needle.setValue(speed);
				speedg.label.setText(speed);

				setCarPos(pos[index][0], pos[index][1]);
				setTimeout(updateValues, 2000);
			}
		</script>
  	</body>
</html>
