<?php
include "../layout/header.php";
include '../helper/databaseConnection.php';
include "../helper/resetHandler.php";
include "../helper/setFocus.php";
//error associative array elements for display error message respective inputs
//user and password variable for storing a user name and password value
$error=array(
    "user" => "",
    "password"=>""
);
$user = "";
$password = "";
//if condition check user is login
if($isLogin) {
    if($_SESSION["users"]["type"]=="admin") header("location:./hotel-list.php");
                        else header("location:./add-beda.php");
}

 if(isset($_POST['login'])){
    //Sanitize and validate the user and password

    $user =filter_var($_POST["user"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    
    $isErr =0;
    if(!$user) {
      $error["user"]="Enter the Phone";
      $isErr++;
    }
    
    if(!$password) {
      $isErr++;
      $error["password"]="Enter the Password";
      
    }
    if($isErr==0)
        {
            //This block is used to connect a database
            $connection=connectDb();
            $passwordEncrypt = md5($password); 
            $query = "CAll login('$user')";
            $data = mysqli_query($connection,$query) or die("login error : ".mysqli_error($connection));
                if($data && mysqli_num_rows($data)){
                    $login = mysqli_fetch_assoc($data);
                    
                    if($login['password']==$passwordEncrypt){

                        $_SESSION["users"]=[
                            "id" => $login["id"],
                            "type" => $login["user_type"]
                        ];
                        $_SESSION["filter"]=[];
                        
                        
                        if($login["user_type"]=="admin"){
                            
                            echo "<script>alert('login successfully');window.location.href='./hotel-list.php'</script>";
                        }  
                        
                        else {
                            echo "<script>alert('login successfully');window.location.href='./add-beda.php'</script>";}
                        
                    }else{
                        $error["password"]="Enter the correct Password";
                    }
                }
                else{
                    $error["user"]="Enter the correct Phone";
                    $error["password"]="Enter the correct Password";
                }
        }
}


?>
<div className="row justify-content-center pt-5">
<div class=" login-card">
    <div class="row justify-content-center">
    <div class="col-md-6 col-12 py-3 px-3">
                <div>
                    <h3 class="title mb-3 py-3 text">Log In</h3>
                        <form method="POST" name="login" onsubmit="return loginValidation()" action="./login.php">
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label for="user" class=" col-md-5 col-12 text">
                                    User Name<span class="text-danger text-bold">*</span>
                                </label>
                                <div class="col-md-7 col-12">
                                    <input type="text" value="<?php echo $user; ?>" name="user" class="form-control" id="user" />
                                    <p class="text-bold text-danger" id="userErr">
                                        <?php
                                        if(!empty($error["user"])){
             
                                            echo $error["user"];
                                        } ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label for="password" class=" col-md-5 col-12 text">
                                    Password<span class="text-danger text-bold">*</span>
                                </label>
                                <div class=" col-md-7 col-12">
                                    <input type="password" value="<?php echo $password; ?>" name="password" class="form-control" id="password" />
                                    <p class="text-bold text-danger" id="passwordErr">
                                        <?php
                                        if(!empty($error["password"])){
             
                                            echo $error["password"];
                                        } ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row  justify-content-center">
                                <div class="py-2">
                                <input value="Reset" onclick="return resetHandler()" class="mr-2 btn btn-danger" type="reset" />
                                    <input name="login" type="submit" class="mr-2 btn btn-dark" value="Log In" />
                                    
                                </div>
                            </div>
                        </form>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                                    </div>
<script>
    setFocus()
    const loginValidation=()=>{
        
        const loginInputs=document.getElementsByClassName("form-control")
        const login={}
        for(let i=0; i< loginInputs.length;++i)
        {
            login[loginInputs[i].name]=loginInputs[i].value
            
        }
     let isErr =0;
     document.getElementById("userErr").innerHTML="";
     document.getElementById("passwordErr").innerHTML="";
      if(!login.user) {
        document.getElementById("userErr").innerHTML="Enter the Phone";
        isErr++
      }
      if(!login.password) {
        isErr++
        document.getElementById("passwordErr").innerHTML="Enter the Password";
      }
      if(isErr==0) return true;
      else {
          setFocus()
          return false;}
    }
</script>
<?php
include "../layout/footer.php";
?>