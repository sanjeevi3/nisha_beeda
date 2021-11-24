<?php
include '../helper/databaseConnection.php';
$email =$_POST["email"];
$password = $_POST["password"];


if(strlen($email)<3 ||  strlen($password) < 3){
    echo "<script>alert('please enter the email and password')</script>";
    echo "<script>location.href = '../view/login.php'</script>";
} else
{
    $database = databaseConnection("hospital_management");
    echo $database["message"];
    if($database["status"]){
        echo $email;
        $query = "SELECT id,name FROM users WHERE email = ".$email;
        $data = mysqli_query($database["data"],$query);
        print_r($data);
    }
} 
{
/* session_start();
$_SESSION["user"]=$_POST;
echo "<script>location.href = '../view/patient-list.php'</script>"; */
}
