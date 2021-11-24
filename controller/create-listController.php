<?php
$name = $_POST["name"];
$phone = $_POST["phone"];
$gender = $_POST["gender"];
$profile = $_POST["profile"];
$address = $_POST["address"]; 
$disease = $_POST["disease"];
$doctor = $_POST["doctor"];
if(strlen($name)<3)
{
    echo errorSender('name');
    echo "<script>location.href = '../view/create-patient.php'</script>";

}else if(strlen($phone)<10)
{
    echo errorSender('phone');
    echo "<script>location.href = '../view/create-patient.php'</script>";

}else  if(!isset($gender))
{
    echo errorSender('gender');
    echo "<script>location.href = '../view/create-patient.php'</script>";

} /* if(strlen($profile)<3)
{
    echo "<script>alert('please enter valid profile')</script>";
    echo "<script>location.href = '../view/register.php'</script>";

} */
else if(!isset($disease))
{
    echo "<script>alert('please enter valid disease')</script>";
    echo "<script>location.href = '../view/create-patient.php'</script>";

}
/* if(empty($doctor))
{
    echo "<script>alert('please enter valid doctor')</script>";
    echo "<script>location.href = '../view/register.php'</script>";

}
 */else
echo "<script>location.href = '../view/patient-list.php'</script>";
 
function errorSender($field){
    return "<script>alert('please enter valid $field')</script>";
}

echo "<pre>";
print_r($_POST);