<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
function dbconnect()
{
	$hostname_MySQL = "localhost";

	if (array_key_exists("DB_HOST", $_SERVER)) {
		$hostname_MySQL = $_SERVER["DB_HOST"];
	}
	
	$database_MySQL = "terciario";
	$username_MySQL = "terciario18";
	$password_MySQL = "nji90okm";
	#$password_MySQL = "IFTS18#2019";
	$MySQL = mysqli_connect($hostname_MySQL, $username_MySQL, $password_MySQL,$database_MySQL ) or trigger_error(mysqli_error(),E_USER_ERROR); 
	mysqli_query($MySQL,"SET NAMES 'utf8'");
	return $MySQL;
}
?>