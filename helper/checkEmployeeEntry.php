<?php
if($_SESSION['users']['type']!="employee"){
    header("location:./hotel-list.php");
}
?>