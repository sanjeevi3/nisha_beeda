<?php
    function getDistrict(){
        $connection=connectDb();
        $districtQuery = "CAll get_district()";
        $districts =[];
        $districtData = mysqli_query($connection,$districtQuery) or die("get districts error : ".mysqli_error($connection));
        if($districtData && mysqli_num_rows($districtData)){
            $districts =[];
            while($row = mysqli_fetch_assoc($districtData)) {
                $districts[]=$row;
          }      
    }
    else{
        echo "<script>alert('Something went wrong! Try again later...');window.location.href='./hotel_list.php'<script>";  
    }
    mysqli_free_result($districtData);
    mysqli_close($connection);
    return $districts;
    }
?>