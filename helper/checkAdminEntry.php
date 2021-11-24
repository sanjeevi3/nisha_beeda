<?php
if($_SESSION['users']['type']!="admin"){
    header("location:./add-beda.php");
}
?>