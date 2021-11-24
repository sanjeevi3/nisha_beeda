<?php
function getProfile($userId){
    $connection=connectDb();
    $query = "CAll get_profile('$userId')";
    $profile=[];
    $data = mysqli_query($connection,$query) or die("add employee error : ".mysqli_error($connection));
     if(mysqli_num_rows($data) && $data){
        $profile = mysqli_fetch_assoc($data);
        
        }
        else{
            echo "<script>alert('could not get profile');</script>";
            
        }
        mysqli_free_result($data);
    mysqli_close($connection);
    return $profile;
}
?>