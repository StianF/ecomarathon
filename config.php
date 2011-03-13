<?PHP 
	include 'db.php';
	if(isset($_GET['action'])){
		$action = $_GET['action'];
		$a = mysql_fetch_assoc(mysql_query("SELECT * FROM config"));
		if($action == "start_clock"){
			if($a[time] == "0000-00-00 00:00:00"){
				mysql_query("UPDATE config SET time = CURRENT_TIMESTAMP, time_status = 1, time_stopped_at = '0000-00-00 00:00:00'");
			}else if($a[time_stopped_at] != "0000-00-00 00:00:00"){
				$now = date("U");
				$last_start = strtotime($a[time]);
				$stopped_at = strtotime($a[time_stopped_at]);
				$new_date = date("Y-m-d H:i:s", $last_start+($now-$stopped_at));
				mysql_query("UPDATE config SET time_status = 1, time_stopped_at = '0000-00-00 00:00:00', time = '".$new_date."'");
			}else{
				mysql_query("UPDATE config SET time_status = 1, time_stopped_at = '0000-00-00 00:00:00'");
			}
		}else if($action == "stop_clock"){
			if($a[time] != "0000-00-00 00:00:00"){
				mysql_query("UPDATE config SET time_status = 0, time_stopped_at = CURRENT_TIMESTAMP");
			}
		}else if($action == "reset_clock"){
			mysql_query("UPDATE config SET time_status = 0, time_stopped_at = '0000-00-00 00:00:00', time = '0000-00-00 00:00:00'");
			mysql_query("UPDATE laps SET time = NULL");
			mysql_query("UPDATE realcps SET visited = 0");
		}
		return;
	}
?>
<html>
	<head>
		<title>Config eco</title>
	</head>
	<body>
<?php
include 'db.php';
if(isset($_POST[config])){
	foreach($_POST[config] as $key => $val){
		mysql_query("UPDATE config SET ".$key." = '".$val."'");
	}
}
if(isset($_POST[laps])){
	foreach($_POST[laps] as $key => $val){
		mysql_query("UPDATE laps SET planned_time = ".$val." WHERE id = ".$key);
	}
}
if(isset($_POST[sensor])){
	foreach($_POST[sensor] as $key => $val){
		foreach($_POST[sensor][$key] as $n => $name){
			mysql_query("UPDATE type_sensor SET name='".$name."' WHERE n = ".$n." AND type_id = ".$key);
		}
	}
}
$config = mysql_query("SELECT * FROM config");
//mysql_query("INSERT INTO config VALUES('http://org.ntnu.no/eitecov11/values2.php', NULL)");
$config = mysql_fetch_assoc($config);
$laps = mysql_query("SELECT * FROM laps");
?>
		<form method="post">
			<div style="float:left">
			<h3>Edit sensor names:</h3>
			<table>
				<tr><th colspan="2">Cell voltages:</th></tr>
				<tr><td>#</td><td>Text</td></tr>
				<?PHP
				$names = mysql_query("SELECT * FROM type_sensor WHERE type_id = 0");
				while($name = mysql_fetch_assoc($names)){
					echo "<tr><td>".$name[n]."</td><td><input type=\"text\" name=\"sensor[".$name[type_id]."][".$name[n]."]\" value=\"".$name[name]."\"></td></tr>";
				}
				?>	
			</table>
			</div>
			<div style="float:left">
			<h3>&nbsp;</h3>
			<table>
				<tr><th colspan="2">Pressure sensor:</th></tr>
				<tr><td>#</td><td>Text</td></tr>
				<?PHP
				$names = mysql_query("SELECT * FROM type_sensor WHERE type_id = 1");
				while($name = mysql_fetch_assoc($names)){
					echo "<tr><td>".$name[n]."</td><td><input type=\"text\" name=\"sensor[".$name[type_id]."][".$name[n]."]\" value=\"".$name[name]."\"></td></tr>";
				}
				?>	
				<tr><th colspan="2">Temperature:</th></tr>
				<tr><td>#</td><td>Text</td></tr>
				<?PHP
				$names = mysql_query("SELECT * FROM type_sensor WHERE type_id = 2");
				while($name = mysql_fetch_assoc($names)){
					echo "<tr><td>".$name[n]."</td><td><input type=\"text\" name=\"sensor[".$name[type_id]."][".$name[n]."]\" value=\"".$name[name]."\"></td></tr>";
				}
				?>	
				<tr><th colspan="2">Pressure:</th></tr>
				<tr><td>#</td><td>Text</td></tr>
				<?PHP
				$names = mysql_query("SELECT * FROM type_sensor WHERE type_id = 3");
				while($name = mysql_fetch_assoc($names)){
					echo "<tr><td>".$name[n]."</td><td><input type=\"text\" name=\"sensor[".$name[type_id]."][".$name[n]."]\" value=\"".$name[name]."\"></td></tr>";
				}
				?>
				<tr><th colspan="2">Output:</th></tr>
				<tr><td>#</td><td>Text</td></tr>
				<?PHP
				$names = mysql_query("SELECT * FROM type_sensor WHERE type_id = 4");
				while($name = mysql_fetch_assoc($names)){
					echo "<tr><td>".$name[n]."</td><td><input type=\"text\" name=\"sensor[".$name[type_id]."][".$name[n]."]\" value=\"".$name[name]."\"></td></tr>";
				}
				?>	
			</table>
			</div>
			<div style="float:left">
			<h3>Other stuff:</h3>
			<table>
				<tr>
					<td>Adress:</td>
					<td><input type="text" name="config[adress_for_data]" value="<?PHP echo $config[adress_for_data];?>"></td>
				</tr>
			</table>
			<table>
				<tr><th>Lap #</th><th>Planned time (in seconds)</th></tr>
				<?PHP 
					while($l = mysql_fetch_assoc($laps)){
				?>
				<tr><td><?PHP echo $l[id];?></td><td><input type="text" name="laps[<?PHP echo $l[id];?>]" value="<?PHP echo $l[planned_time];?>"></td></tr>
				<?PHP
					}
				?>
				<tr><td colspan=2><input type="submit" value="Save"></td></tr>
			</table>
			</div>
					</form>
	</body>
</html>
<?PHP mysql_close($conn);?>
