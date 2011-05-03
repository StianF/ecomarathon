<?PHP 
include "db.php";
$type = $_GET['type'];
$n = $_GET['n'];
$offset = $_GET['offset'];
if(isset($offset)){
	$offset = ($offset*50).",";
}else{
	$offset = "";
}
if(!isset($n) || !isset($type))
	return;
$info = mysql_query("SELECT * FROM type WHERE id = ".$type);
$info = mysql_fetch_assoc($info);
$hist = mysql_query("SELECT * FROM log WHERE type = ".$type." AND n = ".$n." ORDER BY time DESC LIMIT ".$offset."50");

$hista = Array();
while($a = mysql_fetch_assoc($hist)){
	array_push($hista, $a);	
}
$hista = array_reverse($hista);
$a = $hista[0];
$series = "[[".strtotime($a[time])."000,".$a[value]."]";
for($i = 1; $i < count($hista); $i++){
	$a = $hista[$i];
	$series .= ",[".strtotime($a[time])."000,".$a[value]."]";
}
$series .= "]";
mysql_close($conn);

?>
<html>
	<head>
		<!-- BEGIN: load jquery --> 
			<script language="javascript" type="text/javascript" src="jquery-1.4.2.min.js"></script> 
		<!-- END: load jquery --> 

		<!-- BEGIN: highcharts -->
			<script language="javascript" type="text/javascript" src="highcharts.js"></script>
		<!-- END: highcharts -->
		<script type="text/javascript">
			$(document).ready(
				function(){
					chart = new Highcharts.Chart({
						chart: {
							renderTo: 'chart1',
						},
						title: {
							text: "<?PHP echo $info[name]." ".$n;?>"
						},
						xAxis: {
							type: 'datetime'
						},
						tooltip: {
							formatter: function(){
								var date = new Date(this.point.x);
								return "<b>"+date.toUTCString()+'</b>: '+this.y+' <?PHP echo $info[unit];?>';
							}
						},
						yAxis: {
							title: "<?PHP echo $info[unit];?>"
						},
						legend: {
							enabled: false
						},
						plotOptions: {
							series: {
								animation: {
									duration: 2000
								}
							}
						},
						series: [{
							data: <?PHP echo $series;?>
						}]
					});
				}
			);
		</script>

	</head>
	<body>
		<div id="chart1"></div>
		<div>
		<div style="float:left"><a href="stat.php?type=<?PHP echo $type;?>&n=<?PHP echo $n;?><?PHP echo "&offset=".($_GET["offset"]+1);?>">Back</a></div>
		<?PHP 
			if(isset($_GET["offset"]) && $_GET["offset"] != 0){
		?>
				<div style="float:right"><a href="stat.php?type=<?PHP echo $type;?>&n=<?PHP echo $n;?><?PHP echo "&offset=".($_GET["offset"]-1);?>">Forward</a></div>		
		<?PHP
			}
		?>
		</div>
	</body>
</html>

