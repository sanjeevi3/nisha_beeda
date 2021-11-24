<?php
$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$confirmPassword = $_POST["confirm_password"];
if(strlen($name)<3)
{
    echo errorSender('name');
    echo "<script>location.href = '../view/register.php'</script>";

}else if(strlen($email)<3)
{
    echo errorSender('email');
    echo "<script>location.href = '../view/register.php'</script>";

} if(strlen($password)<3)
{
    echo errorSender('password');
    echo "<script>location.href = '../view/register.php'</script>";

} if(strlen($confirmPassword)<3)
{
    echo "<script>alert('please enter valid $field')</script>";
    echo "<script>location.href = '../view/register.php'</script>";

}
else{
    $database = databaseConnection("hospital_management");
    echo $database["message"];
    if($database["status"]){
        echo $email;
        $query = "INSERT INTO  (name,email,password) VALUES($name,$email,".md5($password).")";
        $data = mysqli_query($database["data"],$query);
        print_r($data);
    }
}
//echo "<script>location.href = '../view/patient-list.php'</script>";
 
function errorSender($field){
    return "<script>alert('please enter valid $field')</script>";
}

echo "<pre>";
print_r($_POST);