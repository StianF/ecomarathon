<?PHP
include "db.php";

$types = mysql_query("SELECT * FROM type");
$sum = mysql_fetch_assoc(mysql_query("SELECT sum(n_sensors) as sum FROM type"));
$count = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) as count FROM log"));
$values = mysql_query("SELECT * FROM log LIMIT ".($count[count]-$sum[sum]).",".$sum[sum]);
$real_values = array();
while($v = mysql_fetch_assoc($values)){
	$real_values[$v[type]][$v[n]] = $v[value];
}
while($type = mysql_fetch_assoc($types)){
	$out = "";
	switch($type[id]){
	case 0:
		$out .= "cell_voltage = [";
		break;
	case 1:
		$out .= "pressure_sensor = [";
		break;
	case 2:
		$out .= "temperature = [";
		break;
	case 3: 
		$out .= "pressure = [";
		break;
	case 4:
		$out .= "fuelcell_out = [";
		break;
	}
	for($i = 0; $i < $type[n_sensors]; $i++){
		#$last = mysql_fetch_assoc(mysql_query("SELECT value FROM log WHERE n = ".$i." AND type = ".$type[id]." ORDER BY time DESC LIMIT 1"));
		#$out .= $last[value].",";
		$out .= $real_values[$type[id]][$i].",";
	}
	echo substr($out,0,strlen($out)-1)."];";
}

$cp = mysql_query("SELECT * FROM realcps r JOIN cps c ON c.id = r.cp_id WHERE r.visited = 1 ORDER BY r.id DESC LIMIT 1");
if($cp){
	$last_cp = mysql_fetch_assoc($cp);
}

$sql = mysql_query("SELECT max(id) as id FROM laps WHERE time IS NOT NULL");
$updated_lap = mysql_fetch_assoc($sql);
if($updated_lap[id] != null){
	$updated_lap = mysql_fetch_assoc(mysql_query("SELECT * FROM laps WHERE id = ".$updated_lap[id]));
	$time = $updated_lap[time];
	echo "$('#lap".$updated_lap[id]."').text(\"".floor($time/60).":".str_pad($time%60, 2, "0", STR_PAD_LEFT)."\");";
	$diff = $time -$updated_lap[planned_time] ;
	echo "$('#lapdiff".$updated_lap[id]."').text(\"".(($diff < 0)?"-":"+").floor(abs($diff)/60).":".str_pad(abs($diff)%60, 2, "0", STR_PAD_LEFT)."\");";
	$avgspeed = (3173/$time)*3.6;
	echo "$('#avglap".$updated_lap[id]."').text(\"".round($avgspeed,1)." km/h\");";
	$totaltime = mysql_fetch_assoc(mysql_query("SELECT SUM(time) as sum FROM laps"));
	echo "$('#totaltime').text(\"".floor($totaltime[sum]/60).":".str_pad($totaltime[sum]%60, 2, "0", STR_PAD_LEFT)."\");";
	$diff = mysql_fetch_assoc(mysql_query("SELECT SUM(planned_time) as sum FROM laps"));
	$diff = $totaltime[sum]-$diff[sum];
	echo "$('#totaldiff').text(\"".(($diff < 0)?"-":"+").floor(abs($diff)/60).":".str_pad(abs($diff)%60, 2, "0", STR_PAD_LEFT)."\");";
	if($last_cp[finish] == 1){
		$avgspeed = ($last_cp[distance]/$totaltime[sum])*3.6;
		echo "$('#avgspeed').text(\"".round($avgspeed,1)." km/h\");";
	}
}
$gps = mysql_fetch_assoc(mysql_query("SELECT * FROM gps WHERE id = (SELECT MAX(id) FROM gps)"));
?>
pos = [[<?PHP echo $gps[longitude];?>,<?PHP echo $gps[latitude];?>]];
speed = <?PHP echo $gps[speed];?>;
index = 0;
<?PHP mysql_close($conn); ?>
