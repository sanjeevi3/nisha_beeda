<?php
include "../layout/header.php";
include '../helper/databaseConnection.php';
include "../helper/modalHandler.php";
include "../helper/illegalEntry.php";
$name="";
$district="";
$phone="";
$area="";
$newPhone="";
$newPassword="";
$confirmPassword="";
$userId=$_SESSION["users"]["id"];
$userType=$_SESSION["users"]["type"];
$error=[
    "new_password"=>"",
    "confirm_password"=>"" ,
    "new_phone"=>""
];
if(isset($_SESSION["profile"])){
    $name=$_SESSION["profile"]["name"];
$district=$_SESSION["profile"]["district"];
$phone=$_SESSION["profile"]["phone"];
$area=isset($_SESSION["profile"]["area"])?$_SESSION["profile"]["area"]:"";
}else{
    $connection=connectDb();
    $query = "CAll get_profile('$userId')";
    $data = mysqli_query($connection,$query) or die("add employee error : ".mysqli_error($connection));
     if(mysqli_num_rows($data) && $data){
        $_SESSION["profile"] = mysqli_fetch_assoc($data);
        $name=$_SESSION["profile"]["name"];
        $district=$_SESSION["profile"]["district"];
        $phone=$_SESSION["profile"]["phone"];
        $area=isset($_SESSION["profile"]["area"])?$_SESSION["profile"]["area"]:"";
        }
        else{
            if($userType=="admin"){
                echo "<script>alert('something went wrong! Try again later...');window.location.href='./hotel-list.php'</script>";
            }else{
                echo "<script>alert('something went wrong! Try again later...');window.location.href='./add-beda.php'</script>";
            }
            
            
        }
        mysqli_free_result($data);
    mysqli_close($connection);
}
if(isset($_POST["change_password"])){
    $passwordRegExp="/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,15}$/";
    $confirmPassword=$_POST["confirm_password"];
    $newPassword=$_POST["new_password"];
    
    $isErr=0;
    if(!preg_match($passwordRegExp,$newPassword)){
        $isErr++;
             $error["new_password"]="Password must contain one letter,one symbol,one number and 8-15 characters";
    }  
    if($confirmPassword!=$newPassword)  {
        $error["confirm_password"]="new password and confirm password doesn't match";
        $isErr++;
    }
    if($isErr==0){
        $password = md5($newPassword);
        
        $connection=connectDb();
            $query = "CAll change_password('$password','$userId')";
            $data = mysqli_query($connection,$query) or die("add employee error : ".mysqli_error($connection));
            print_r($data);
             if(gettype($data)=="boolean" && $data){
                $_SESSION["profile"]["password"]=$newPassword;
                echo "<script>alert('Change Password successfully');window.location.href='./profile.php'</script>";
                }
                else{
                        echo "<script>alert('Something went wrong! Try again later...');window.location.href='./profile.php?modal=password&newPassword=".$newPassword."&confirmPassword=".$confirmPassword."'</script>";
                    }
                    
                
    }
    else{
        header("location:./profile.php?modal=password");
    }
        }

elseif(isset($_POST["change_phone"])){
    $isErr=0;
    $phoneRegExp="/^[0-9]{10,10}$/";
    $newPhone=$_POST["new_phone"];
    if(!preg_match($phoneRegExp,$newPhone)){
        $isErr++;
             $error["new_phone"]="Phone number must contain numbers";
    }
    if($isErr==0){
        
        $userId=$_SESSION["users"]["id"];
        $connection=connectDb();
            $query = "CAll change_phone('$userId','$newPhone')";
            $data = mysqli_query($connection,$query) or die("add employee error : ".mysqli_error($connection));
             if(gettype($data)=="boolean" && $data){
                $_SESSION["profile"]["phone"]=$newPhone;
                echo "<script>alert('Change phone successfully');window.location.href='./profile.php'</script>";
                }
                else{
                    $changePassword = mysqli_fetch_assoc($data);
                    print_r($changePassword);
                    if($changePassword["existPhone"]){
                        header("location:./profile.php?modal=phone&exist=1&phone=".$newPhone);
                    }else{
                        echo "<script>alert('something went wrong! Try again later.....');window.location.href='./profile.php'</script>";
                    }
                    
                }
    }
    else{
        header("location:./profile.php?modal=password");
    }
}
?>

<div id="passwordModalBackDrop" class="back-drop d-none" onclick="return passwordCloseModal()"></div>
<div id="passwordModal" class='my-modal modal-hide ' >
            <div class="header">
                <h3>Change Password</h3>
            </div>
            <div class="body">    
            <form method="POST" name="change_password" onsubmit="return passwordValidation()"  action="./profile.php">
            <div class="row mb-2 col-md-6 px-0 mx-0 ">
                                <label for="NewPassword" class=" col-md-5 col-12 text-dark">
                                    Enter new password<span class="text-danger text-bold">*</span>
                                </label>
                <div class=" col-md-7 col-12">
                    <input type="text" value="<?php echo $newPassword; ?>" class="form-control" name="new_password" id="newPassword"/>
                    <p class="text-bold text-danger" id="newPasErr">
                                    <?php
                                        if(!empty($error["new_password"])){
             
                                                echo $error["new_password"];
                                         } ?>
                                    </p>
                </div>
            </div>
            <div class="row mb-2 col-md-6 px-0 mx-0 ">
                                <label class=" col-md-5 col-12 text-dark" for="confirmPassword">
                                    Confirm new password<span class="text-danger text-bold">*</span>
                                </label>
                                <div class="col-md-7 col-12">
                                    <input type="text" value="<?php echo $confirmPassword; ?>" class="form-control" name="confirm_password" id="confirmPassword"/>
                                    <p class="text-bold text-danger" id="confirmPasErr">
                                    <?php
                                        if(!empty($error["confirm_password"])){
             
                                                echo $error["confirm_password"];
                                         } ?>
                                    </p> 
                                </div>
            </div>
            <div class="row col-md-6 justify-content-center">
                                <div class="py-2">
                                    <input name="change_password" type="submit" class="mr-2 btn btn-success" value="Change Password" />
                                </div>
            </div>
            </form>
            </div>      
               
</div>

<div id="phoneModalBackDrop" class="back-drop d-none" onclick="return phoneCloseModal('')"></div>
<div id="phoneModal" class='my-modal modal-hide '>
            <div class="header">
                <h3>Change Phone Number</h3>
            </div>
            <div class="body">
                
            <form method="POST" name="change_phone" onsubmit="return phoneValidation()" action="./profile.php">
            <div class="row mb-2 col-md-6 px-0 mx-0 ">
                                <label for="changePhone" class=" col-md-5 col-12 text-dark">
                                    Phone number<span class="text-danger text-bold">*</span>
                                </label>
                <div class=" col-md-7 col-12">
                    <input type="text" value="<?php echo ""; ?>" class="form-control" name="new_phone" id="newPhone"/>
                    <p class="text-bold text-danger" id="newPhoneErr">
                                    <?php
                                        if(!empty($error["new_phone"])){
             
                                                echo $error["new_phone"];
                                         } ?>
                                    </p> 
                </div>
            </div>
           
            <div class="row col-md-6 justify-content-center">
                                <div class="py-2">
                                    <input name="change_phone" type="submit" class="mr-2 btn btn-success" value="Change Number" />
                                </div>
            </div>
            </form>
            
            </div>      
               
</div>

<div class="row justify-content-center">
<div class="col-12 col-md-6 login-card">
    <div class="row ">
    <div class=" col-12 py-3 px-3">
                <div>
                    <h3 class="title col-md-10 mb-3 py-3 text">Profile</h3>
                        <div class=" mb-2 row justify-content-center col-12">
                            <div class="col-md-5 col-7 px-0 font-weight-bold text-capitalize text">Name :</div>
                            <div class="col-md-5 col-5 px-0 text-capitalize text"><?php echo $name; ?></div>
                        </div>
                        <div class=" mb-2 justify-content-center row col-12">
                            <div class="col-md-5 col-7 px-0 font-weight-bold text-capitalize text">District :</div>
                            <div class="col-md-5 col-5 px-0 text-capitalize text"><?php echo $district; ?></div>
                        </div>
                        <?php
                        echo $userType=="employee"?'<div class=" mb-2 justify-content-center row col-12">
                        <div class="col-md-5 col-7 px-0 font-weight-bold text-capitalize text">Area :</div>
                        <div class="col-md-5 col-5 px-0 text-capitalize text">'. $area .'</div>
                    </div>':"";
                        ?>
                        <div class=" mb-2 justify-content-center row col-12">
                            <div class="col-md-5 col-7 px-0 font-weight-bold text-capitalize text">Phone Number :</div>
                            <div class="col-md-5 col-5 px-0 text-capitalize text"><?php echo $phone; ?></div>
                        </div>
                        <div class="row  col-12 justify-content-center">
                                <div class="py-2">
                                    <input name="changePassword" type="button" onclick="return openModal('passwordModal','passwordModalBackDrop')" class="mr-2 mb-2 btn btn-dark" value="Change Password" /> 
                                    <input name="changePhoneNumber" type="button" onclick="return openModal('phoneModal','phoneModalBackDrop')" class="mr-2 mb-2 btn btn-dark" value="Change Phone"/>
                                </div>
                        
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const params = new URLSearchParams(location.search)
    if(params.get("modal")=="password"){
        openModal('passwordModal','passwordModalBackDrop')
       // if(params.get("validate")) passwordValidation();
    } 
    else if(params.get("modal")=="phone"){
        openModal('phoneModal','phoneModalBackDrop')
        if(params.get("exist")) {
            document.getElementById("newPhone").value=params.get("phone")
            document.getElementById('newPhoneErr').innerHTML="this Phone number already exist. please choose another"
        }
       // if(params.get("validate")) phoneValidation();
    }
const passwordValidation=()=>{
    
    const passwordRegExp=/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,15}$/;
    const passwordInputs=document.getElementsByClassName("form-control")
    const addPassword={}
        for(let i=0; i<passwordInputs.length;++i)
        {
            addPassword[passwordInputs[i].id]=passwordInputs[i].value
        }
        
    let isErr=0;
    document.getElementById('newPasErr').innerHTML=""
    document.getElementById('confirmPasErr').innerHTML=""
    if(!addPassword.newPassword.match(passwordRegExp)){
        isErr++
             document.getElementById('newPasErr').innerHTML="Password must contain one letter,one symbol,one number and 8-15 characters"
    }  
    if(addPassword.confirmPassword!=addPassword.newPassword)  {
        document.getElementById('confirmPasErr').innerHTML="new password and confirm password doesn't match"
        ++isErr
    }
    if(isErr==0){
        return true
    }
    else{
        return false
    }
}
const phoneValidation=()=>{
    
    document.getElementById('newPhoneErr').innerHTML=""
    let isErr=0;
    const phoneRegExp=/^[0-9]{10,10}$/;
    const newPhone=document.getElementById("newPhone").value
    if(!newPhone.match(phoneRegExp)){
        isErr++
             document.getElementById('newPhoneErr').innerHTML="Phone number must contain numbers and 10 digit"
    }
    if(isErr==0){
        return true
    }
    else{
        return false
    }
}
const passwordCloseModal=()=>{
    document.getElementById('newPasErr').innerHTML=""
    document.getElementById('confirmPasErr').innerHTML=""
    closeModal("passwordModal","passwordModalBackDrop")
}
const phoneCloseModal=()=>{
    document.getElementById('newPhoneErr').innerHTML=""
    closeModal("phoneModal","phoneModalBackDrop")
}
</script>
<?php
include "../layout/footer.php";
?>