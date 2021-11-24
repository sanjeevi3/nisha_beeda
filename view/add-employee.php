<?php
include "../layout/header.php";
include '../helper/databaseConnection.php';
include "../helper/illegalEntry.php";
include "../helper/checkAdminEntry.php";
include "../helper/getDistrict.php";
include "../helper/getArea.php";
include "../helper/resetHandler.php";
include "../helper/setFocus.php";
$fName="";
$lName="";
$phone="";
$district="";
$area="";

$nameRegExp="/^[a-zA-Z]{3,15}$/";
$phoneRegExp="/^[0-9]{10,10}$/";
$districtQuery = "CAll get_district()";
$districts=[];
$areaList=[];
if(isset($_SESSION["districts"])){
    $districts=$_SESSION["districts"];
}else{
    $districts = getDistrict();
    $_SESSION["districts"]=$districts;
}
if((isset($_GET["district"])||isset($_POST["district"]))){ 
    if(isset($_POST["district"])){
    $fName = $_POST["first_name"];
    $lName = $_POST["last_name"];
    $phone = $_POST["phone"];
    $district = $_POST["district"];
    }
    else{
    $fName = $_GET["first_name"];
    $lName = $_GET["last_name"];
    $phone = $_GET["phone"];
    $district = $_GET["district"];
    }
    if($district!=""){
       
        $areaList=getArea($district);
    }
        
   
}
if(isset($_POST['add_employee'])){
    $fName = $_POST["first_name"];
    $lName = $_POST["last_name"];
    $phone = $_POST["phone"];
    $district = $_POST["district"];
    $area=$_POST["area"];
    $error=array(
        "first_name"=>"",
        "last_name"=>"",
        "phone"=>"",
        "district"=>""
    );
    $isErr =0;
    if($fName=="") {
      $error["first_name"]="Enter the first name";
      $isErr++;
    }else if(!preg_match($nameRegExp,$fName)){
      $isErr++;
      $error["first_name"]="first name must be minimum 3 letters and string only";
    }
    if($lName=="") {
      $isErr++;
      $error["last_name"]="Enter the last name"; 
    }else if(!preg_match($nameRegExp,$lName)){
      $isErr++;
      $error["last_name"]="last name must be minimum 3 letters and string only";
    }
    if($phone==""){
        $isErr++;
        $error["phone"]="Enter the phone number";
    }else if(!preg_match($phoneRegExp,$phone)){
        $isErr++;
        $error["phone"]="Enter the correct format of phone number";
    }
    
    if($district==""){
        $error["district"]="Select the district";
        $isErr++;
    }
    if($area==""){
        $error["area"]="Select the area";
        $isErr++;
    }
    if($isErr==0)
        {
            $userId=$_SESSION["users"]["id"];
            $connection=connectDb();
            $password = md5("beda@3");
            $query = "CAll add_user('$fName','$lName','$phone','$district','$area','$password',$userId)";
            $data = mysqli_query($connection,$query) or die("add employee error : ".mysqli_error($connection));
            if($data)if(gettype($data)=="boolean" && $data){
                echo "<script>alert('Add Employee successfully');window.location.href='./hotel-list.php'</script>";
            }
            else{
                $addEmployee=mysqli_fetch_assoc($data);
                        if(isset($addEmployee["reachUserLimit"])){
                            echo "<script>alert('Employee limit reached.please upgrade application')<script>";
                        }else if(isset($addEmployee["existPhone"])||isset($addEmployee["existName"])){
                            if($addEmployee["existPhone"]){
                                $error["phone"]="your phone is already exist.";
                            }
                            if($addEmployee["existName"]) {
                                $error["first_name"]="your name is already exist.";
                                $error["last_name"]="your name is already exist.";
                            }
                        }
                        else{
                            echo "<script>alert('Something went wrong! Try again later....');window.location.href='./hotel-list.php'<script>";
                        }

                    
                    
                }
               
    

    }
}
?>

<div className="row justify-content-center pt-5">
<div class=" login-card">
    <div class="row justify-content-center ">
    <div class="col-md-8 col-12 py-3 px-3">
                <div>
                    <h3 class="title mb-3 py-3 text">Add Employee</h3>
                        <form method="POST" name="add_employee"  onsubmit="return addValidation()" action="./add-employee.php">
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label  class=" col-md-5 col-12 text" for="firstName">
                                    First Name<span class="text-danger text-bold">*</span>
                                </label>
                                <div class="col-md-7 col-12">
                                    <input type="text" value="<?php echo $fName; ?>" name="first_name" class="form-control" id="firstName" />
                                    <p class="text-bold text-danger" id="firstNameErr">
                                    <?php
                                        if(!empty($error["first_name"])){
             
                                                echo $error["first_name"];
                                         } ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label class=" col-md-5 col-12 text" for="lastName">
                                    Last Name<span class="text-danger text-bold">*</span>
                                </label>
                                <div class=" col-md-7 col-12">
                                    <input type="text" value="<?php echo $lName; ?>" class="form-control" name="last_name" id="lastName" /> 
                                    <p class="text-bold text-danger" id="lastNameErr">
                                    <?php
                                        if(!empty($error["last_name"])){
             
                                            echo $error["last_name"];
                                        } ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label class=" col-md-5 col-12 text" for="phone">
                                    PhoneNumber<span class="text-danger text-bold">*</span>
                                </label>
                                <div class=" col-md-7 col-12">
                                <input type="text" value="<?php echo $phone; ?>" name="phone" class="form-control" id="phone" />
                                    <p class="text-bold text-danger" id="phoneErr">
                                    <?php
                                        if(!empty($error["phone"])){
             
                                         echo $error["phone"];
                                     } ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label class=" col-md-5 col-12 text" for="district">
                                    District<span class="text-danger text-bold">*</span>
                                </label>
                                <div class=" col-md-7 col-12">
                                    <select onchange="return districtChangeHandler()" id="district"  name="district" class="form-control">
                                        <option value="" >Select</option>
                                        <?php foreach ($districts as $dt) {?>
                                        <option value="<?php echo $dt["id"]; ?>" <?php echo $dt["id"]==$district?"selected":""; ?>><?php echo $dt["district"]; ?></option>
                                        <?php }?>
                                    </select> 
                                    <p class="text-bold text-danger" id="districtErr">
                                    <?php
                                        if(!empty($error["district"])){
                                        echo $error["district"];
                                    }
                                ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label class=" col-md-5 col-12 text" for="district">
                                    Area<span class="text-danger text-bold">*</span>
                                </label>
                                <div class=" col-md-7 col-12">
                                    <select id="area" <?php  echo $district=="" || count($areaList)==0?"disabled":""; ?>  name="area" class="form-control">
                                        <option value="" >Select</option>
                                        <?php foreach ($areaList as $dt) {?>
                                        <option value="<?php echo $dt["id"]; ?>" <?php echo $dt["id"]==$area?"selected":""; ?>><?php echo $dt["name"]; ?></option>
                                        <?php }?>
                                    </select> 
                                    <p class="text-bold text-danger" id="areaErr">
                                    <?php
                                        if(!empty($error["area"])){
                                        echo $error["area"];
                                    }
                                ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row  justify-content-center">
                                <div class="py-2">
                                <input value="Reset" onclick="return resetHandler()" class="mr-2 btn btn-danger" type="reset" />
                                <input name="add_employee" type="submit" class="mr-2 btn btn-dark" value="Add Employee" />  
                                
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
    var isErr =0;
    setFocus()
    const params = new URLSearchParams(location.search)
    const districtChangeHandler=()=>{
        let firstName = document.getElementById("firstName").value;
        let lastName = document.getElementById("lastName").value;
        let phone = document.getElementById("phone").value;
        let district = document.getElementById("district").value;
        
        if(isErr==0){
            location.replace("../view/add-employee.php?validate=0&first_name="+firstName+"&last_name="+lastName+"&phone="+phone+"&district="+district);
        }else{
            location.replace("../view/add-employee.php?validate=1&first_name="+firstName+"&last_name="+lastName+"&phone="+phone+"&district="+district);
        }
        

    }
    const addValidation=()=>{
        let phoneRegExp =/^[0-9]{10,10}$/;
        let nameRegExp =/^[a-zA-Z]{3,15}$/;
        const employeeInputs=document.getElementsByClassName("form-control")
        const add_employee={}
        for(let i=0; i< employeeInputs.length;++i)
        {
            add_employee[employeeInputs[i].id]=employeeInputs[i].value
        }
      let isErr =0;
      document.getElementById("firstNameErr").innerHTML="";
      document.getElementById("lastNameErr").innerHTML="";
      document.getElementById("phoneErr").innerHTML="";
      document.getElementById("districtErr").innerHTML="";
      if(add_employee.firstName=="") {
        document.getElementById("firstNameErr").innerHTML="Enter the first name";
        isErr++
      }else if(!add_employee.firstName.match(nameRegExp)){
          isErr++
          document.getElementById("firstNameErr").innerHTML="first name must be minimum 3 letters and string only"
      }
      
      
      if(add_employee.lastName=="") {
        isErr++
        document.getElementById("lastNameErr").innerHTML="Enter the last name";
      }else if(!add_employee.lastName.match(nameRegExp)){
          isErr++
          document.getElementById("lastNameErr").innerHTML="last name must be minimum 3 letters and string only"
      }
      
      if(add_employee.phone==""){
          isErr++
          document.getElementById("phoneErr").innerHTML="Enter the phone number";
      }else if(!add_employee.phone.match(phoneRegExp)){
          isErr++
          document.getElementById("phoneErr").innerHTML="Enter the correct format of phone number";
      }
      if(add_employee.district==""){
          isErr++
        document.getElementById("districtErr").innerHTML="select the area";
      }
      if(add_employee.area==""){
          isErr++
        document.getElementById("areaErr").innerHTML="select the area";
      }
      
      if(isErr==0) return true;
      else{ 
          setFocus()
          return false; }
    }
    if(params.get("validate")=="1") addValidation();
    
</script>
<?php
include "../layout/footer.php";
?>