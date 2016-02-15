<?
ini_set(mysql.connect_timeout,5);
date_default_timezone_set('Europe/Paris');
/* Primary */
$db_ip = "localhost";
$db_login = "urlshorter";
$db_pwd = "urlshorter";
$db_name = "urlshorter";

// Create connection
$conn = new mysqli($db_ip, $db_login, $db_pwd,$db_name);

$sql = "SET GLOBAL time_zone = 'Europe/Paris'";
$result = $conn->query($sql);
?>
