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
$config = mysql_query("SELECT * FROM config");
//mysql_query("INSERT INTO config VALUES('http://org.ntnu.no/eitecov11/values2.php', NULL)");
$config = mysql_fetch_assoc($config);
$laps = mysql_query("SELECT * FROM laps");
?>
		<form method="post">
			<table>
				<tr><th colspan=2>Config:</th></tr>
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
		</form>
	</body>
</html>
<?PHP mysql_close($conn);?>
