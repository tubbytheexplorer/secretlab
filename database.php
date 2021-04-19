<?php
class DbConnect {

  function __construct() 
  {

  }

  function OpenConn()
 {
   $dbhost = "localhost";
   $dbuser = "root";
   $dbpass = "";
   $db = "db_secretlab";
   $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
   
   return $conn;
 }
 
function CloseConn($conn)
 {
    $conn -> close();
 }

}   
?>
