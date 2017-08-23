<?php

$id = $_POST["id"];

$servername = "localhost";
$username = "root";
$password = "axzsax22";
$dbname = "promo";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT claster FROM users WHERE id=".$id.";";
$result = $conn->query($sql);

$row = $result->fetch_assoc();
echo preg_replace('/^[ \t]*[\r\n]+/m', '', $row['claster']);

?>


