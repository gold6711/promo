<?php

include_once 'profile.php';

$myData;

$link = mysqli_connect("localhost", "root", "", "promo");
mysqli_query($link, "INSERT INTO `supply_mat`( `given_mat` ) VALUES( $myData )");

?>