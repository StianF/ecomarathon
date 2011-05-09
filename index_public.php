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
			<script type="text/javascript" src="getPublicData.php"></script> 
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
		<div style="width:1250px;center;  margin-left: auto ;margin-right: auto ;">
			<center>
			<div id="header"></div>
			<div id="clock">00 : 00 : 00</div>
		<!--	<table><tr><td><img src="DNV.jpg" height="100px"></td><td><h1>Shell Eco Marathon 2011</h1></td><td><img src="shell.jpg" height="100px"></td></tr></table>-->
			</center>
			<hr>
			<div id="content">
				<div>
					<div style="float:left;margin-top:5px;">
						<table border="1" width="500px">
							<tr><th>Lap #</th><th>Actual Time</th><th>Planned time</th><th>Diff</th><th>Avg speed</th></tr>
							<?PHP $times = mysql_query("SELECT * FROM laps");
								$totalplanned = 0;
								$totaltime = 0;
							while($lap = mysql_fetch_assoc($times)){
								$totalplanned += $lap[planned_time];
								$totaltime += $lap[time];
								echo "<tr>";
								echo "<td>".$lap[id]."</td>";
								echo "<td id=\"lap".$lap[id]."\">".(($lap[time] != "")?floor($lap[time]/60).":".str_pad($lap[time]%60, 2, "0", STR_PAD_LEFT):"")."</td>";
								echo "<td>".floor($lap[planned_time]/60).":".str_pad($lap[planned_time]%60, 2, "0", STR_PAD_LEFT)."</td>";
								$diff = $lap[time]-$lap[planned_time];
								echo "<td id=\"lapdiff".$lap[id]."\">".(($lap[time] != "")?(($diff < 0)?"-":"+").floor(abs($diff)/60).":".str_pad(abs($diff)%60, 2, "0", STR_PAD_LEFT):"")."</td>";
								echo "<td id=\"avglap".$lap[id]."\">".(($lap[time] != 0)?round((3173/$lap[time])*3.6, 2)." km/h":"")."</td>";
								echo "</tr>";
							}
							?>
							<tr><td>Total</td>
								<td id="totaltime"><?PHP echo floor($totaltime/60).":".str_pad($totaltime%60, 2, "0", STR_PAD_LEFT);?></td>
								<td><?PHP echo floor($totalplanned/60).":".str_pad($totalplanned%60, 2, "0", STR_PAD_LEFT);?></td>
								<?PHP $diff = $totaltime-$totalplanned;?>
								<td id="totaldiff"><?PHP echo (($diff != 0)?(($diff < 0)?"-":"+").floor(abs($diff)/60).":".str_pad(abs($diff)%60, 2, "0", STR_PAD_LEFT):"");?> </td>
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
					<div style="float:left;margin-top:5px;margin-left:40px;">
						<div style="text-align:center;">Speed:</div>
						<div id="speed" style="width:150px;height:150px;"></div>
					</div>
				</div>
				<div style="float:right;">			
					<div id="map_canvas" style="width: 500px; height: 400px"></div>
					<form>
					Fixed map to track: <input type="radio" name="followcar" onClick="unsetFollow();" checked >
					Follow Car: <input type="radio" name="followcar" onClick="setFollow();">
					</form>
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
			var speedg = bindows.loadGaugeIntoDiv("gauge.xml", "speed");

			<?PHP 
			if ($_SESSION[config][time] != "0000-00-00 00:00:00"){
				$time = strtotime("now")-strtotime($_SESSION[config][time]);
				echo "var sec = ".($time%60).";";
				echo "var min = ".(floor($time/60)%60).";";
				echo "var hour = ".floor($time/3600).";";
				if($_SESSION[config][time_status] == 1){
					echo "stopwatch();";
				}
			}else{	
			?>
				var sec = 0;
				var min = 0;
				var hour = 0;
			<?PHP
			}
			?>
			//<!--Get values-->
			$(function () {
				updateValues();
			});
			function updateValues() {
				$.getScript('getPublicData.php', function(){updateUI();});
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
