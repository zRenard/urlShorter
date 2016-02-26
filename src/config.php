<?php
date_default_timezone_set('Europe/Paris');
/* Primary */
$db_ip = getenv('IP');; //"localhost";
$db_login = getenv('C9_USER'); //"urlshorter";
$db_pwd = ""; //"urlshorter";
$db_name = "c9"; //"urlshorter";
$db_port = 3306;
 
// Create connection
$conn = new mysqli($db_ip, $db_login, $db_pwd,$db_name,$db_port);

$sql = "SET GLOBAL time_zone = 'Europe/Paris'";
$result = $conn->query($sql);
?>
