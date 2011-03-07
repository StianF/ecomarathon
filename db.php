<?php
$dbhost = '127.0.0.1';
$dbuser = 'eit';
$dbpass = '';

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die                      ('Error connecting to mysql');

$dbname = 'eco';
mysql_select_db($dbname);
?>
