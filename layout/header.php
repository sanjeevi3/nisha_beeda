<?php

$isLogin;
session_start();
$currentNav="";
if(isset($_SESSION["current_nav"])){
 $currentNav= $_SESSION["current_nav"];
}
if(isset($_SESSION["users"])){
   $isLogin=true;
   $userType=$_SESSION["users"]["type"];
   if($userType=="admin"){
     $navItems=[
      ["stock list","hotel-list"],
       ["add employee","add-employee"],
       ["add hotel","add-hotel"],
       
       ["profile","profile"]
     ];
   }
   else{
    $navItems=[
      ["add stock","add-beda"],
      ["profile","profile"]
      
    ];
   }
}
else{
  $isLogin = false;
}
?>
<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
    <link rel="icon" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#000000">
    <meta name="description" content="Web site created using create-react-app">
    <link rel="apple-touch-icon" href="/logo192.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../assets/css/index.css"/>
    
  </head>
  <body>
    <script>
      const menuToggleClickHandler=()=>{
          
        let sideNavClass=document.getElementById("sideNav").className.split(" ");
        if(sideNavClass.indexOf("opened")!=-1){
          sideNavClass.splice(sideNavClass.indexOf("opened"),1,"closed")
          document.getElementById("backDrop").className="back-drop d-none"
        }
        else{
          sideNavClass.splice(sideNavClass.indexOf("closed"),1,"opened")
          document.getElementById("backDrop").className="back-drop"
        }
        document.getElementById("sideNav").className=sideNavClass.join(" ")
      }
      const removeSideNav=()=>{
        let sideNavClass=document.getElementById("sideNav").className.split(" ");
        sideNavClass.splice(sideNavClass.indexOf("opened"),1,"closed")
        document.getElementById("backDrop").className="back-drop d-none"
        document.getElementById("sideNav").className=sideNavClass.join(" ")
      }
      const navClick=(event)=>{

        location.replace(event.target.children[0].href);
       
      }
    </script>
    <header  >
      <div class="row mx-0">
        <div class="col-2 logo"><a><img src="../assets//image//theme/logo.jfif"></a></div>
        <div class='col-md-8 mt-3 tittle-block col-8'>
          <h2 class="text-center text-bold  text">NISHA BEEDA</h2>
        </div>
        
        <div class="col-2 mt-3 drawer-toggle-block">
          <?php 
            if($isLogin){
          echo '<div onclick="return menuToggleClickHandler()" class="drawer-toggle px-2 d-block d-md-none">
            <div></div>
            <div class="my-2"></div>
            <div></div>
          </div>';
       }?>
      </div>
           
        
      </header>
     
      <?php
      
            if($isLogin){
              $navList="";
      foreach ($navItems as $navItem) {
        
        $navList = $navList . "<li onclick='return navClick(event)' class='pl-5 my-1'><a href='../view/".$navItem['1'].".php' >".$navItem['0']."</a></li>";
        } 
          echo '<div id="backDrop" class="back-drop d-none" onclick="return removeSideNav()"></div>
      <div id="sideNav"  class="col-md-2 col-8 px-1 navigation-block closed">
       <nav>
       <ul id="navigation" class="navigation">
       '.$navList.'
       
      <li class="pl-5" onclick="return navClick(event)"><a href="../view/logout.php" >log out</a></li> 
          </ul>
       </nav>
        </div>';
        echo '<script>
      let navList=document.getElementById("navigation").children
      for(let i =0; i<navList.length;i++){
        if(navList[i].children[0].href==location.href.split("?")[0]){
          navList[i].className=navList[i].className+" active"
        }
      }
      
    </script>';
      } 
      
      ?>
       
          <div class="<?php echo $isLogin?"col-md-10 page-block":"login-page" ?> col-12 ">
          <div class="row justify-content-center py-3">
          <div class="col-11 p-3  my-card">
      
           
      