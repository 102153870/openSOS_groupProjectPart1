<?php
// settings.php to store connection variables
$host = "localhost";     
$user = "root";   
$password  = ""; 
$sql_db = "OPENSOS"; // Database name

$conn = mysqli_connect($host, $user, $password, $sql_db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

