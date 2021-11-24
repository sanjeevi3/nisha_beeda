<?php
     function getArea($district){
        $areaQuery = "CAll get_area('$district')";
        $connection=connectDb();
        $areaList =[];
        $areaData = mysqli_query($connection,$areaQuery) or die("get districts error : ".mysqli_error($connection));
        if($areaData && mysqli_num_rows($areaData)){
            
              while($row = mysqli_fetch_assoc($areaData)) {
                $areaList[]=$row;
                
              }      
            }else{
                if(mysqli_num_rows($areaData)==0){
                    echo "<script>alert('This district has no area. please choose other district');</script>";
                }else
                echo "<script>alert('Something went wrong! Try again later...');window.location.href='./hotel_list.php'<script>";    
            }
        mysqli_free_result($areaData);
        mysqli_close($connection);
        return $areaList;
     }
?>