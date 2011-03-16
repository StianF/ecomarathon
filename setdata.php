<?PHP
include "db.php";
session_start();
$values = $_POST['data'];

if(isset($_POST['data'])){
echo $_POST['data'];
	$values = preg_split("/,/",$values, -1, PREG_SPLIT_NO_EMPTY);
	$out = "";
	$current = "NA";
	$current_n = 0;
#	$maxdate = mysql_fetch_assoc(mysql_query("SELECT max(time) as time FROM log"));
#	$save_db = (strtotime("now")-strtotime($maxdate[time]))>10;
	$save_db = true;
	$gps = array();

	function save_to_db($t,$n,$v){
		$type = -1;
		if($t == "V"){
			$type = 0;
		}else if($t == "S"){
			$type = 1;
		}else if($t == "T"){
			$type = 2;
		}else if($t == "P"){
			$type = 3;
		}else if($t == "O"){
			$type = 4;
		}
		mysql_query("INSERT INTO log (type,n,value) VALUES (".$type.",".$n.",".$v.")");
	}
	foreach($values as $v){
		if($v == "V"){
			$current = $v;
			$out .= "cell_voltage = [";
			$current_n = 0;
		}else if($v == "S"){
			$current = $v;
			$current_n = 0;
		}else if($v == "T"){
			$current = $v;
			$current_n = 0;
		}else if($v == "P"){
			$current = $v;
			$current_n = 0;
		}else if($v == "O"){
			$current = $v;
			$current_n = 0;
		}else if($v == "G"){
			$current = $v;
			$current_m = 0;
		}else if($v == "err"){
			if($save_db){
				save_to_db($current,$current_n++,-1);
			}
		}else if($v != "\n"){
			if($save_db){
				if($current == "G"){
					array_push($gps, $v);
				}else{
					save_to_db($current,$current_n++,$v);
				}
			}
		}
	}
	if($save_db && count($gps) >= 3){
		mysql_query("INSERT INTO gps(latitude,longitude,speed) VALUES('".$gps[0]."','".$gps[1]."',".$gps[2].")");
	}



	$cp = mysql_fetch_assoc(mysql_query("SELECT r.id as rid, r.*, c.* FROM realcps r JOIN cps c ON c.id = r.cp_id WHERE visited = 0 ORDER BY r.id ASC LIMIT 1"));
	$last_cp = mysql_query("SELECT * FROM realcps r JOIN cps c ON c.id = r.cp_id WHERE r.visited = 1 AND r.id < ".$cp[rid]." AND c.finish = 1 ORDER BY r.id DESC LIMIT 1");
	if($last_cp){
		$last_cp = mysql_fetch_assoc($last_cp);
	}

	$lat = $gps[1];
	$long = $gps[0];

	$started = mysql_fetch_assoc(mysql_query("SELECT * FROM config"));

	if($cp[visited] == 0 && $started[time_status] == 1){
		$ok = false;
		if($cp[direction] == 1 && ($long < $cp[p1lo] || $long < $cp[p2lo]) && $lat < $cp[p1la] && $lat > $cp[p2la]){
			$ok = true;
		}else if($cp[direction] == 2 && ($long > $cp[p1lo] || $long > $cp[p2lo]) && $lat < $cp[p1la] && $lat > $cp[p2la]){
			$ok = true;
		}
		if($ok){
			mysql_query("UPDATE realcps SET visited = 1, visited_at = CURRENT_TIMESTAMP WHERE id = ".$cp[rid]);
			if($cp[finish] == 1){
				$id = mysql_fetch_assoc(mysql_query("SELECT min(id) as id, laps.* FROM laps WHERE time IS NULL"));
				$time = strtotime("now")-((!$last_cp)?strtotime($started[time]):strtotime($last_cp[visited_at]));
				mysql_query("UPDATE laps SET time = ".$time." WHERE id = ".$id[id]);
			}
		}
	}
}
?>
<?PHP mysql_close($conn); ?>
