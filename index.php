<?PHP 
include 'login.php';
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
		<link rel="stylesheet" type="text/css" href="popup.css" />	

		<script type="text/javascript" src="common.js"></script> 
		<script type="text/javascript" src="popup.js"></script>

		<!-- BEGIN: load jquery --> 
			<script type="text/javascript" src="jquery-1.4.2.min.js"></script> 
		<!-- END: load jquery --> 

		<!-- BEGIN: stopwatch -->
			<script type="text/javascript" src="stopwatch.js"></script> 
		<!-- END: stopwatch -->		
	
		<!-- BEGIN: prefetch values-->
			<script type="text/javascript" src="<?PHP echo $_SESSION[config][adress_for_data];?>"></script> 
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
			<a href="config.php" class='submodal-800-520' >Config</a>
			<center>
			<div id="header"></div>
		<!--	<table><tr><td><img src="DNV.jpg" height="100px"></td><td><h1>Shell Eco Marathon 2011</h1></td><td><img src="shell.jpg" height="100px"></td></tr></table>-->
				<form name="clock"><input type="text" name="stwa" value="00 : 00 : 00"><input type="button" name="theButton" onClick="stopwatchButton(this.value);" value="Start"><input type="button" onClick="stopwatchButton(this.value);reset();" value="Reset"></form>
			</center>
			<hr>
			<div style="float:top;font-size:75%">
			<input type="button" onClick="showhide('cellvolt');" id="cellvoltbut" value="Cell Voltage">
			<div style="display:none;" id="cellvolt">
				<table>
					<tr>
				<?PHP
					for($i = 0; $i < 23; $i++){
						$name = mysql_fetch_assoc(mysql_query("SELECT name FROM type_sensor WHERE type_id = 0 AND n = ".$i));
						echo "<td>".$name[name]."</td>";
					}
					echo "</tr><tr>";
					for($i = 0; $i < 23; $i++){
						#echo "<td onclick=\"window.open('http://81.167.78.33/eco/stat.php?type=0&n=".$i."');\" style=\"cursor: pointer\" id=\"cell".$i."\"></td>";
						echo "<td><a class='submodal-800-520' href=\"stat.php?type=0&n=".$i."\" style=\"text-decoration:  none;cursor: pointer\" id=\"cell".$i."\"></a></td>";
					}
					echo "</tr><tr>";
					for($i = 23; $i < 46; $i++){
						$name = mysql_fetch_assoc(mysql_query("SELECT name FROM type_sensor WHERE type_id = 0 AND n = ".$i));
						echo "<td>".$name[name]."</td>";
					}
					echo "</tr><tr>";
					for($i = 23; $i < 46; $i++){
						#echo "<td onclick=\"window.open('http://81.167.78.33/eco/stat.php?type=0&n=".$i."');\" style=\"cursor: pointer\" id=\"cell".$i."\"></td>";
						echo "<td><a class='submodal-800-520' href=\"stat.php?type=0&n=".$i."\" style=\"text-decoration:  none;cursor: pointer\" id=\"cell".$i."\"></a></td>";
					}
				?>
					</tr>
				</table>
			</div>
			<input type="button" onClick="showhide('sumcellvolt');" id="sumcellvoltbut" value="Sum of Cell Voltages">
			<div style="display:none;" id="sumcellvolt">
				<table>
					<tr>
				<?PHP
					$name = mysql_fetch_assoc(mysql_query("SELECT name FROM type_sensor WHERE type_id = 1 AND n = 0"));
					echo "<td>".$name[name]."</td>";
					echo "</tr><tr>";
					echo "<td><a class='submodal-800-520' href=\"stat.php?type=1&n=0\" style=\"text-decoration:  none;cursor: pointer\" id=\"sumcell\"></a></td>";
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
						$name = mysql_fetch_assoc(mysql_query("SELECT name FROM type_sensor WHERE type_id = 2 AND n = ".$i));
						echo "<td>".$name[name]."</td>";
					}
					echo "</tr><tr>";
					for($i = 0; $i < 12; $i++){
						//echo "<td onclick=\"window.open('http://81.167.78.33/eco/stat.php?type=2&n=".$i."');\" style=\"cursor: pointer\" id=\"tempsens".$i."\"></td>";
						echo "<td><a class='submodal-800-520' href=\"stat.php?type=2&n=".$i."\" style=\"text-decoration:  none;cursor: pointer\" id=\"tempsens".$i."\"></a></td>";

					}
				?>
					</tr>
				</table>
			</div>
			<input type="button" onClick="showhide('outputv');" id="outputvbut" value="Output Voltage">
			<div style="display:none;" id="outputv">
				<table>
					<tr>
				<?PHP
					for($i = 0; $i < 5; $i++){
						$name = mysql_fetch_assoc(mysql_query("SELECT name FROM type_sensor WHERE type_id = 4 AND n = ".$i));
						echo "<td>".$name[name]."</td>";
					}
					echo "</tr><tr>";
					for($i = 0; $i < 5; $i++){
						//echo "<td onclick=\"window.open('http://81.167.78.33/eco/stat.php?type=4&n=".$i."');\" style=\"cursor: pointer\" id=\"outputvo".$i."\"></td>";
						echo "<td><a class='submodal-800-520' href=\"stat.php?type=4&n=".$i."\" style=\"text-decoration:  none;cursor: pointer\" id=\"outputvo".$i."\"></a></td>";
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
					for($i = 0; $i < 2; $i++){
						$name = mysql_fetch_assoc(mysql_query("SELECT name FROM type_sensor WHERE type_id = 3 AND n = ".$i));
						echo "<td>".$name[name]."</td>";
					}
					echo "</tr><tr>";
					for($i = 0; $i < 2; $i++){
					//	echo "<td onclick=\"window.open('http://81.167.78.33/eco/stat.php?type=3&n=".$i."');\" style=\"cursor: pointer\" id=\"pressures".$i."\"></td>";
						echo "<td><a class='submodal-800-520' href=\"stat.php?type=3&n=".$i."\" style=\"text-decoration:  none;cursor: pointer\" id=\"pressures".$i."\"></a></td>";
					}
				?>
					</tr>
				</table>
			</div>
			</div>
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
			var speed = 0.0;
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
					echo "stopwatch(\"Start\");";
				}
			}else{	
			?>
				var sec = 0;
				var min = 0;
				var hour = 0;
			<?PHP
			}
			?>
			<?PHP
				$thre = mysql_query("SELECT * FROM type_sensor");
				$thresholds = array();
				while($t = mysql_fetch_assoc($thre)){
					$thresholds[$t[type_id]][$t[n]] = array($t[min],$t[max]);
				}
				$out = "";
				foreach($thresholds as $o){
					$out .= (($out == "")?"[":",[");
					foreach($o as $i){
						if(substr($out, count($out)-1, strlen($out))=="[,"){
							$out = substr($out, 0, strlen($out)-1);
						}
						$out .= ((substr($out, strlen($out)-1, strlen($out)) == "[")?"[":",[").$i[0].",".$i[1]."]";
					}
					$out .= "]";
				}
				echo "var threshold = [".$out."];";
			?>
			function showhide(div) {

				if($("#"+div).css("display") == "none"){
					$("#"+div).css("display", "block");
				}else{
					$("#"+div).css("display", "none");
				}
				$("#"+div+"but").css("background-color", "");
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
					if(cell_voltage[i] < threshold[0][i][0] || cell_voltage[i] > threshold[0][i][1]){
						$("#cell"+i).css("color", "red");
						found = true;
					}else if($("#cell"+i).css("color") == "red" || $("#cell"+i).css("color") == "rgb(255, 128, 64)"){
						$("#cell"+i).css("color", "#FF8040");
					}else{
						$("#cell"+i).css("color", "green");
					}
				}
				if(found){
					$("#cellvoltbut").css("background-color", "red");
				}
				found = false;
				for(i = 0; i < 12; i++){
					$("#tempsens"+i).text(temperature[i]+" C");
					if(temperature[i] < threshold[2][i][0] || temperature[i] > threshold[2][i][1]){
						$("#tempsens"+i).css("color", "red");
						found = true;
					}else if($("#tempsens"+i).css("color") == "red" || $("#tempsens"+i).css("color") == "rgb(255, 128, 64)"){
						$("#tempsens"+i).css("color", "#FF8040");
					}else{
						$("#tempsens"+i).css("color", "green");
					}
				}
				if(found){
					$("#temperaturebut").css("background-color", "red");
				}
				found = false;
				for(i = 0; i < 5; i++){
					$("#outputvo"+i).text(fuelcell_out[i]+" V");
					if(fuelcell_out[i] < threshold[4][i][0] || fuelcell_out[i] > threshold[4][i][1]){
						$("#outputvo"+i).css("color", "red");
						found = true;
					}else if($("#outputvo"+i).css("color") == "red" || $("#outputvo"+i).css("color") == "rgb(255, 128, 64)"){
						$("#outputvo"+i).css("color", "#FF8040");
					}else{
						$("#outputvo"+i).css("color", "green");
					}
				}
				if(found){
					$("#outputvbut").css("background-color", "red");
				}
				found = false;
				for(i = 0; i < 2; i++){
					$("#pressures"+i).text(pressure[i]+" Pa");
					if(pressure[i] < threshold[3][i][0] || pressure[i] > threshold[3][i][1] || pressure[i] == "err"){
						$("#pressures"+i).css("color", "red");
						found = true;
					}else if($("#pressures"+i).css("color") == "red" || $("#pressures"+i).css("color") == "rgb(255, 128, 64)"){
						$("#pressures"+i).css("color", "#FF8040");
					}else{
						$("#pressures"+i).css("color", "green");
					}
				}
				if(found){
					$("#pressurebut").css("background-color", "red");
				}
				found = false;
				$("#sumcell").text(sumcell[0]+" V");
				if(sumcell[0] < threshold[1][0][0] || sumcell[0] > threshold[1][0][1]){
					$("#sumcell").css("color", "red");
					found = true;
				}else if($("#sumcell").css("color") == "red" || $("#sumcell").css("color") == "rgb(255, 128, 64)"){
					$("#sumcell").css("color", "#FF8040");
				}else{
					$("#sumcell").css("color", "green");
				}
				if(found){
					$("#sumcellvoltbut").css("background-color", "red");
				}
				setCarPos(pos[index][0], pos[index][1]);
				speedg.needle.setValue(speed);
				speedg.label.setText(speed);
				//$("#speed").text(speed);
				setTimeout(updateValues, 2000);
			}
			function stopwatchButton(value){
				if(value == 'Start'){
					$.ajax({url: "http://81.167.78.33/ecomarathon/config.php?action=start_clock"});
					stopwatch(value);
				}else if(value == 'Stop '){
					$.ajax({url: "http://81.167.78.33/ecomarathon/config.php?action=stop_clock"});
					stopwatch(value);		
				}else if(value == 'Reset'){
					$.ajax({url: "http://81.167.78.33/ecomarathon/config.php?action=reset_clock"});
					for(i = 1; i <= 6; i++){
						$("#lap"+i).text('');
						$("#lapdiff"+i).text('');
						$("#avglap"+i).text('');
					}
					$("#avgspeed").text('');
					$("#totaldiff").text('');
					$("#totaltime").text('');
					resetIt();
				}
			}
		</script>
  	</body>
</html>
