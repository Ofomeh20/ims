<?php

$servername="localhost";
$username="root";
$password="";
$dbname="library";
$conn = new mysqli ($servername , $username , $password , $dbname);

if ($conn->connect_error){
    die("connection failed: ". $conn->connect_error);
}

//ENABLE  MYSQLI 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try{
$conn->query("ALTER  TABLE books ADD COLUMN available VARCHAR(50) DEFAULT 'Yes';");
$conn->query("ALTER TABLE books ADD COLUMN status VARCHAR(50) DEFAULT 'Available'; ");
$cinn->commit(); //appy changes if everythng iis okay
echo"Transaction succesfull";

}catch(mysli_sql_execution $e){
    //undo changes if any query  fails 
    echo "Error: " . $e->   getMessage(); 
}
$conn->close();
?>