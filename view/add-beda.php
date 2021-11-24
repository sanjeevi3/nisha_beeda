<?php
include "../layout/header.php";
include '../helper/databaseConnection.php';
include "../helper/illegalEntry.php";
include "../helper/checkEmployeeEntry.php";
include "../helper/resetHandler.php";
include "../helper/setFocus.php";
include "../helper/getProfile.php";
$count="";
$district=isset($_GET["district"])?$_GET["district"]:"";
$hotel="";
$hotels =[];
$userId=$_SESSION["users"]["id"];
$moneyreceived="";
$addMoneyRegExp="/^[0-9]{0,}$/";
if(isset($_SESSION["profile"])){
$area=$_SESSION["profile"]["areaId"];
}else{
        $_SESSION["profile"] = getProfile($userId);
        $area=$_SESSION["profile"]["areaId"];
}
if(isset($_SESSION["hotels"])){
    $hotels=$_SESSION["hotels"];
}else{
    $hotelQuery = "CAll get_hotel('$area')";
    $connection=connectDb();
    $hotelData = mysqli_query($connection,$hotelQuery) or die("get districts error : ".mysqli_error($connection));
    if($hotelData && mysqli_num_rows($hotelData)){
          while($row = mysqli_fetch_assoc($hotelData)) {
            $hotels[]=$row;
          }     
          $_SESSION["hotels"]=$hotels; 
    }else  $error["hotel"]="we have no hotels in your area.";
    mysqli_free_result($hotelData);
    mysqli_close($connection);
}
if(isset($_POST['add_beda'])){
    $count =$_POST["refill_count"];
    $hotel = $_POST["select_hotel"];
    $moneyreceived = $_POST["money_received"];
    $error=array(
        "refill_count"=>"",
        "select_hotel"=>"",
        "money_received"=>""
    );
    $isErr =0;
    if($count!=""&&!preg_match($addMoneyRegExp,$count)) {
      $error["refill_count"]="Enter the count and numbers only";
      $isErr++;
    }
    if($hotel=="") {
      $isErr++;
      $error["select_hotel"]="Select the hotel";
    }
    if($moneyreceived!=""&&!preg_match($addMoneyRegExp,$moneyreceived)){
        $isErr++;
        $error["money_received"]="Enter the money received and numbers only";
    }
    if($count==""&&$moneyreceived==""){
        echo "<script> alert('enter the count or money received!') </script>";
    } 
    if($isErr==0){
        $userId=$_SESSION["users"]["id"];
        $connection=connectDb();
            $query = "CAll add_stock('$hotel','1','$count','$moneyreceived','$userId')";
            $data = mysqli_query($connection,$query) or die("add employee error : ".mysqli_error($connection));
             if(gettype($data)=="boolean" && $data){
                 echo "<script> alert('successfully added your stock'); window.location.href='./add-beda.php' </script>" ;  
                }
                else{
                    
                        echo "<script>alert('Something went wrong! Try again later...');window.location.href='./add-beda.php'<script>";
            
                }
    }
}  
?>
<div className="row justify-content-center pt-5">
<div class="login-card">
    <div class="row justify-content-center">
       
            <div class="col-md-8 col-12 py-3 px-3">
                <div>
                    <h3 class="title mb-3 py-3 text">Refill Beda</h3>
                    <form method="POST" name="addBeda"  onsubmit="return addBedaValidation()" action="./add-beda.php">
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label for="selectHotel" class=" col-md-5 col-12 text">
                                Select Hotel<span class="text-danger text-bold">*</span>
                                </label>
                                <div class="col-md-7 col-12">
                                <select id="selectHotel"    name="select_hotel" class="form-control">
                                    <option value="" >Select</option>
                                    <?php foreach ($hotels as $hl) {?>
                                    <option value="<?php echo $hl["id"]; ?>" <?php echo $hl["id"]==$hotel?"selected":""; ?>><?php echo $hl["name"]; ?></option>
                                    <?php }?>
                                </select>
                                    <p class="text-bold text-danger" id="selectHotelErr">
                                    <?php
                                        if(!empty($error["select_hotel"])){
                                            echo $error["select_hotel"];
                                        } ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label for="refillCount" class=" col-md-5 col-12 text">
                                Refill Count<span class="text-danger text-bold">*</span>
                                </label>
                                <div class="col-md-7 col-12">
                                <input type="text" value="<?php echo $count; ?>" name="refill_count" class="form-control" id="refillCount" />
                                    <p class="text-bold text-danger" id="refillCountErr">
                                        <?php
                                        if(!empty($error["refill_count"])){
             
                                            echo $error["refill_count"];
                                        } ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-2 col-12 px-0 mx-0 ">
                                <label for="moneyReceived" class=" col-md-5 col-12 text">
                                Money Received<span class="text-danger text-bold">*</span>
                                </label>
                                <div class="col-md-7 col-12">
                                <input type="text" value="<?php echo $moneyreceived; ?>" name="money_received" class="form-control" id="moneyReceived" />
                                    <p class="text-bold text-danger" id="moneyReceivedErr">
                                        <?php
                                        if(!empty($error["money_received"])){
             
                                            echo $error["money_received"];
                                             } ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row  justify-content-center">
                                <div class="py-2">
                                <input value="Reset" onclick="return resetHandler()" class="mr-2 btn btn-danger" type="reset" />
                                    <input name="add_beda" type="submit" class="mr-2 btn btn-dark" value="Add Stock" />        
                                    
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
    const addBedaValidation=()=>{
        isErr=0
        let addMoneyReqExp=/^[0-9]{0,}$/
        const addBedaInputs=document.getElementsByClassName("form-control")
        const addBeda={}
        for(let i=0; i< addBedaInputs.length;++i)
        {
            addBeda[addBedaInputs[i].id]=addBedaInputs[i].value
        }
      document.getElementById("refillCountErr").innerHTML="";
      document.getElementById("selectHotelErr").innerHTML="";
      document.getElementById("moneyReceivedErr").innerHTML="";
      if(addBeda.refillCount!=""&&!addBeda.refillCount.match(addMoneyReqExp)){
          document.getElementById("refillCountErr").innerHTML="Enter Refill Count and numbers only"
          isErr++
      }
      if(addBeda.selectHotel==""){
          isErr++
        document.getElementById("selectHotelErr").innerHTML="select the hotel";
      }
      if(addBeda.moneyReceived!=""&&!addBeda.moneyReceived.match(addMoneyReqExp)){
          isErr++
          document.getElementById("moneyReceivedErr").innerHTML="Enter the money received and numbers only";
      }
      if(addBeda.refillCount==""&&addBeda.moneyReceived==""&&isErr==0){
          isErr++
          alert("Enter count or Enter money received!");
      }
      
      if(isErr==0){ 
      return true;}
      else {
          setFocus()
          return false;} 
    }
</script>
<?php
include "../layout/footer.php";
?>