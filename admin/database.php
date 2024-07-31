<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "sophm";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}
   
    // $host = "localhost";  
    // $user = "root";  
    // $password = '';  
    // $db_name = "sophm";  
      
    // $con = mysqli_connect($host, $user, $password, $db_name);  
    // if(mysqli_connect_errno()) {  
    //     die("Failed to connect with MySQL: ". mysqli_connect_error());  
    // }  
?>  