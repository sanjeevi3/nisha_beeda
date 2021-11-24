<?php
include "../layout/header.php";
include "../helper/databaseConnection.php";
include "../helper/illegalEntry.php";
include "../helper/modalHandler.php";
include "../helper/checkAdminEntry.php";


if(isset($_POST['apply'])){
$date=$_POST["date"];
$date=date("Y-m-d",strtotime($date));
$_SESSION["filter"]["add_stock_list"]=[
    "date"=>$date
];
}else{
    $date=date("Y-m-d");
}
if(isset($_SESSION["filter"]["add_stock_list"])){
    $date=$_SESSION["filter"]["add_stock_list"]["date"];
}
$connection=connectDb();
$query="call get_add_stock_list('$date',0)";
$addStockList =[];
$addStockData = mysqli_query($connection,$query) or die("get districts error : ".mysqli_error($connection));
    if($addStockData && mysqli_num_rows($addStockData)){
        
          while($row = mysqli_fetch_assoc($addStockData)) {
            $addStockList[]=$row;
          }      
    }
    mysqli_free_result($addStockData);
    mysqli_close($connection);
?>
<div id="modalBackDrop" class="back-drop d-none" onclick="return closeModal('filterModal','modalBackDrop')"></div>
<div id="filterModal" class='my-modal modal-hide '>
            <div class="header">
                <h3>Filter</h3>
            </div>
            <div class="body">
            <form method="POST" name="login" action="./hotel-list.php">
            <div class="row mb-2 col-md-6 px-0 mx-0 ">
                                <label class=" col-md-5 col-12 text-dark" for="date">
                                    Date<span class="text-danger text-bold">*</span>
                                </label>
                                <div class="col-md-7 col-12">
                                    <input type="date" value="<?php echo $date; ?>" class="form-control" name="date" id="date"/> 
                                </div>
            </div>
            <!-- <div class="row mb-2 col-md-6 px-0 mx-0 ">
                                <label for="employeeName" class=" col-md-5 col-12 text-dark">
                                    Employee Name
                                </label>
                <div class=" col-md-7 col-12">
                    <select id="employeeName" class="form-control">            
                        <option value="">Select</option>
                        <option value="Tirunelveli">sanjai</option>
                        <option value="Tuticorin">Sanjeevi</option>
                        <option value="Madurai">Gokul</option>
                    </select>
                </div>
            </div>
            <div class="row mb-2 col-md-6 px-0 mx-0 ">
                                <label for="area" class=" col-md-5 col-12 text-dark">
                                    Area
                                </label>
                <div class=" col-md-7 col-12">
                    <select id="area" class="form-control">            
                        <option value="">Select</option>
                        <option value="Tirunelveli">Tirunelveli</option>
                        <option value="Tuticorin">Tuticorin</option>
                        <option value="Madurai">Madurai</option>
                    </select>
                </div>
            </div>
             -->
            
            <div class="col-md-7 col-12 row justify-content-center">
                                    <input type="submit" value="Apply" class="mr-2 btn btn-success" name="apply" id="apply"/> 
                                </div>

            </form>
            </div>      
               
</div>

<h3 class="text-center text">Stock List</h3>

                           <?php 
                           if(count($addStockList)!=0) {
                           echo "<a href='download.php?date=".$date."'><input name='downloadButton' class='btn btn-dark' type='button' value='Download'/></a>";
                           }?>
<button type="button" class="mb-3 btn btn-primary float-right" onclick="return openModal('filterModal','modalBackDrop')">filter</button>
<?php
$table ='<table class="table table-responsive">
                            <thead><tr onclick=""><th>S.No</th><th>Hotel Name</th><th>Stock</th><th>Received</th><th>Total</th><th>Balance</th><th>Employee Name</th><th>Time</th><th></th></tr></thead>
                            <tbody>';
                             
                            
                            for($i=0;$i<count($addStockList);$i++){
                            $sl=$i+1;
                            
                                $table=$table. "<tr><td>".$sl."</td>
                                <td>".$addStockList[$i]['hotel']."</td>
                                <td>".$addStockList[$i]['stock_count']."</td>
                                <td>".$addStockList[$i]['recived_money']."</td>
                                <td>".$addStockList[$i]['total_price']."</td>
                                <td>".$addStockList[$i]['balance_price']."</td>
                                <td>".$addStockList[$i]['employee']."</td>
                                <td>".$addStockList[$i]['time']."</td></tr>";  
                              
                            }
                            
                            

                            
                            $table=$table."</tbody></table>";
                            
                           if(count($addStockList)==0) {
                           echo "<p class='text-center mt-5 text text-danger'> no stock was added in ".date_format(date_create($date),"j M y")."</p>";
                           }
                           
                           else echo $table;
                           ?>
<script>
// var today = new Date();
// var dd = String(today.getDate()).padStart(2, '0');
// var mm = String(today.getMonth() + 1).padStart(2, '0'); 
// var yyyy = today.getFullYear();
// today = yyyy + '-' + mm + '-' + dd;
// document.getElementById("date").defaultValue=today   
</script>
<?php
include "../layout/footer.php";
?>