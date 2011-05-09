<?PHP
include "db.php";

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
