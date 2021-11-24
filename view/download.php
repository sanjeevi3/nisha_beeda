<?php
include "../helper/databaseConnection.php";
$date=$_GET['date'];
$fileName="addStockList".date_format(date_create($date),"jMy").".xls";
$connection=connectDb();
$query="call get_add_stock_list('$date',0)";
$html="<table class='xl-table'>
<thead><tr><th>S.No</th><th>Hotel Name</th><th>Stock</th><th>Received</th><th>Total</th><th>Balance</th><th>Employee Name</th><th>Time</th></tr></thead>
<tbody>";
$addStockData = mysqli_query($connection,$query) or die("get districts error : ".mysqli_error($connection));
    if($addStockData && mysqli_num_rows($addStockData)){
        $addStockList =[];
            $i=0;
          while($row = mysqli_fetch_assoc($addStockData)) {
            $i=$i+1;
            $html.="<tr><td>".$i."</td>
            <td>".$row['hotel']."</td>
            <td>".$row['stock_count']."</td>
            <td>".$row['recived_money']."</td>
            <td>".$row['total_price']."</td>
            <td>".$row['balance_price']."</td>
            <td>".$row['employee']."</td>
            <td>".$row['time']."</td></tr>";
          } 
          $html.="</tbody></table>";   
    }
    mysqli_free_result($addStockData);
    mysqli_close($connection);
    
header('Conent-Type:application/xls');
header('Content-Disposition:attachment;filename='.$fileName);
echo "<style>table,th,td{border:1px solid black} </style>";
echo $html;
?>
