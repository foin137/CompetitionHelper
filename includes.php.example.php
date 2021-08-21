<?php 
$id = "user"; // user name
  $pw = "password"; // Password
  $host = "host"; // Host ("localhost" or "IP")
  $db = "database"; // database name
   $mysqli = new MySQLi(
        $host,
        $id,
        $pw,
        $db
      );
       
      if (mysqli_connect_errno()) {   
        exit;
      }
      ?>