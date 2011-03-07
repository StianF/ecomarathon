<?PHP 
include "db.php";
$type = $_GET['type'];
$n = $_GET['n'];
if(!isset($n) || !isset($type))
	return;
$info = mysql_query("SELECT * FROM type WHERE id = ".$type);
$info = mysql_fetch_assoc($info);
$hist = mysql_query("SELECT * FROM log WHERE type = ".$type." AND n = ".$n." ORDER BY time DESC LIMIT 50");

$a = mysql_fetch_assoc($hist);
$series = "[['".$a[time]."',".$a[value]."]";
while($a = mysql_fetch_assoc($hist)){
	$series .= ",['".$a[time]."',".$a[value]."]";
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

						},
						yAxis: {
							title: "<?PHP echo $infor[unit];?>"
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
		<div id="chart1">
	</body>
</html>

