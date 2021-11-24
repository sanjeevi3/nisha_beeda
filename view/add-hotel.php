<?php
include "../layout/header.php";
include '../helper/databaseConnection.php';
include "../helper/illegalEntry.php";
include "../helper/checkAdminEntry.php";
include "../helper/getDistrict.php";
include "../helper/getArea.php";
include "../helper/resetHandler.php";
include "../helper/setFocus.php";
//districts and areaList array has to store district and area data from the database
$hotel="";
$district="";
$area="";
$address="";
$hotelRegExp="/^[a-zA-z ]{5,30}$/";
$districts=[];
$areaList=[];
//check district data in session
if(isset($_SESSION["districts"])){
    $districts=$_SESSION["districts"];
}else{
    //get district Data from db
    $districts = getDistrict();
    $_SESSION["districts"]=$districts;
}
// check district is selected;
if((isset($_GET["district"])||isset($_POST["district"]))){
    if(isset($_POST["district"])){
        $district=$_POST["district"];
    $hotel =$_POST["hotel"];
    }
    else{
        $district=$_GET["district"];
    $hotel =$_GET["hotel"];
    }
    if($district!=""){
        //get area Data from db
        $areaList=getArea($district);
    }
   
}


if(isset($_POST['add_hotel'])){
   //validate
    $hotel =$_POST["hotel"];
    $district = $_POST["district"];
    $area=$_POST["area"];
    $address=$_POST["address"];
    $error=array(
        "hotel"=>"",
        "district"=>"",
        "area"=>"",
        "address"=>""
    );
    $isErr =0;
    if($hotel=="") {
      $error["hotel"]="Enter the hotel name";
      $isErr++;
    }else if(!preg_match($hotelRegExp,$hotel)){
        $error["hotel"]="hotel name must contains minimum 5 letters and string only";
    }
    if($district==""){
        $error["district"]="Select the district";
        $isErr++;
    }
    if($area==""){
        $error["area"]="Select the area";
        $isErr++;
    }
    if($address==""){
        $error["address"]="Select the address";
        $isErr++;
    }
    if($isErr==0){
        $userId=$_SESSION["users"]["id"];
        $latitude="273567";
        $longitude="45646346";
        // get same area hotels point from db
        $connection=connectDb();
        $hotelsPointList=[];
        $hotelsPointQuery = "CAll get_hotels_point('$area')";
        $hotelsPointData = mysqli_query($connection,$hotelsPointQuery) or die("add employee error : ".mysqli_error($connection));
         if(mysqli_num_rows($hotelsPointData) && $hotelsPointData){
            while($hotelsPointRow = mysqli_fetch_assoc($hotelsPointData)) {
                $hotelsPointList[]=$hotelsPointRow;
                
              }    
            }
            else{
                if(mysqli_num_rows($hotelsPointData)==0){
                    $hotelsPointList=[];
                }else
                    echo "<script>alert('Something went wrong! Try again later...');window.location.href='./hotel-list.php'<script>";
            }
            mysqli_free_result($hotelsPointData);
            mysqli_close($connection);
            if(count($hotelsPointList)){
                
            }
            // add hotels to DB
            $connection=connectDb();
            $query = "CAll add_hotel('$hotel','$district','$area','$address','$latitude','$longitude','$userId')";
            $data = mysqli_query($connection,$query) or die("add employee error : ".mysqli_error($connection));
             if(gettype($data)=="boolean" && $data){
                echo "<script>alert('Add Hotel successfully');window.location.href='./hotel-list.php'</script>";
                }
                else{
                    $addHotel = mysqli_fetch_assoc($data);
                    if(isset($addHotel["reachLimit"])){
                        echo "<script>alert('Hotel limit reached.please upgrade application')<script>";
                    }else if(isset($addHotel["existHotel"])){
                        if($addHotel["existHotel"]){
                            $error["hotel"]="this hotel name is already exist.";
                        }
                    }else{
                        echo "<script>alert('Something went wrong! Try again later...');window.location.href='./hotel-list.php'<script>";
                    }
    }}
}
?>
<div className="row justify-content-center pt-5">
<div class=" login-card">
    <div class="row justify-content-center">
    <div class="col-md-8 col-12 py-3 px-3">
                <div>
                    <h3 class="title mb-3 py-3 text">Add Hotel</h3>
                    <form method="POST" name="add_hotel" onsubmit="return addHotelValidation()" action="./add-hotel.php">
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label  class=" col-md-5 col-12 text" for="hotel">
                                    Hotel Name<span class="text-danger text-bold">*</span>
                                </label>
                                <div class="col-md-7 col-12">
                                <input type="text" value="<?php echo $hotel; ?>" name="hotel" class="form-control" id="hotel" />
                                    <p class="text-bold text-danger" id="hotelErr">
                                    <?php
                                        if(!empty($error["hotel"])){
             
                                            echo $error["hotel"];
                                           }   ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label class=" col-md-5 col-12 text" for="district">
                                    District<span class="text-danger text-bold">*</span>
                                </label>
                                <div class=" col-md-7 col-12">
                                    <select id="district"  onchange="return districtChangeHandler()"  name="district" class="form-control">
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
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label class=" col-md-5 col-12 text" for="district">
                                    Address<span class="text-danger text-bold">*</span>
                                </label>
                                <div class=" col-md-7 col-12">
                                <textarea name="address" id="address"  class="form-control" ></textarea>
                                    <p class="text-bold text-danger" id="addressErr">
                                    <?php
                                        if(!empty($error["address"])){
                                        echo $error["address"];
                                    }
                                ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row  justify-content-center">
                                <div class="py-2">
                                <input value="Reset" onclick="return resetHandler()" class="mr-2 btn btn-danger" type="reset" />
                                <input name="add_hotel" type="submit" class="mr-2 btn btn-dark" value="Add Hotel" />
                                
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
        let district = document.getElementById("district").value;
        let hotel = document.getElementById("hotel").value;
        if(isErr==0){
            location.replace("../view/add-hotel.php?validate=0&hotel="+hotel+"&district="+district);
        }else{
            location.replace("../view/add-hotel.php?validate=1&hotel="+hotel+"&district="+district);
        }
        

    }
    const addHotelValidation=()=>{
        isErr=0
        let hotelNameRegExp=/^[a-zA-Z\s]{5,30}$/
        const hotelInputs=document.getElementsByClassName("form-control")
        const add_hotel={}
        for(let i=0; i< hotelInputs.length;++i)
        {
            add_hotel[hotelInputs[i].id]=hotelInputs[i].value
        }
      
      document.getElementById("hotelErr").innerHTML="";
      document.getElementById("districtErr").innerHTML="";
      document.getElementById("areaErr").innerHTML="";
      document.getElementById("addressErr").innerHTML="";
      if(add_hotel.hotel=="") {
        document.getElementById("hotelErr").innerHTML="Enter the hotel name";
        isErr++
      }else if(!add_hotel.hotel.match(hotelNameRegExp)){
          isErr++
          document.getElementById("hotelErr").innerHTML="hotel name must be minimum 3 letters and not contain numbers and symbols";
      }
      if(add_hotel.district==""){
          isErr++
        document.getElementById("districtErr").innerHTML="select the district";
      }
      if(add_hotel.area==""){
          isErr++
        document.getElementById("areaErr").innerHTML="select the area";
      }
      if(add_hotel.address==""){
          isErr++
        document.getElementById("addressErr").innerHTML="enter the address";
      }
      
      if(isErr==0) return true;
      else {
          setFocus()
          return false; 
          }
    }
    if(params.get("validate")==1) addHotelValidation();
</script>

<?php
include "../layout/footer.php";
?>