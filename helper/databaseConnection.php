<?php
include '../config.php';
function connectDb(){
    $database = constant('DATABASE');
    $host = constant('HOST');
    $user = constant('USER');
    $password = constant('PASSWORD');
    $connection = mysqli_connect($host,$user,$password) or die("connection error : ".mysqli_connect_error());
	$db = mysqli_select_db($connection,$database);
    return $connection;
}
?>


