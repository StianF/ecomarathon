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
		$i = mysql_query("UPDATE config SET ".$key." = '".$val."'");
	}
}
$config = mysql_query("SELECT * FROM config");
//mysql_query("INSERT INTO config VALUES('http://org.ntnu.no/eitecov11/values2.php', NULL)");
$config = mysql_fetch_assoc($config);
?>
		<form method="post">
			<table>
				<tr>
					<td>Adress:</td>
					<td><input type="text" name="config[adress_for_data]" value="<?PHP echo $config[adress_for_data];?>"></td>
					<tr><td colspan=2><input type="submit"></td></tr>
				</tr>
			</table>
		</form>
	</body>
</html>
<?PHP mysql_close($conn);?>
