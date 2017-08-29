<?
error_reporting(~E_DEPRECATED);
$hostname = "localhost";
$username = "root";
$password = "";
$dbName = "promo";


mysql_connect($hostname, $username, $password) or die ("Не могу создать соединение");
mysql_query('SET NAMES utf8') or header('Location: Error');


mysql_select_db($dbName) or die (mysql_error());
?>