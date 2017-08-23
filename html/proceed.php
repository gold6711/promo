<?php

include("config.php");

$oid = 1;
$ord = 1;
$user = $_POST['user'];
$summa = $_POST['summa'];
$cdate = time(c);
$comment = '111';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO kassa (oid, ord, user, summa, cdate, comment) 
VALUES (".$oid.",".$ord.",".$user.",".$summa.",".$cdate.",".$comment.");";

if ($conn->query($sql) === TRUE) {
    echo "Счет оплачен.";
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>