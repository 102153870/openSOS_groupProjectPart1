<?php
    // settings.php to store connection variables
    $host = "localhost";     
    $user = "root";   
    $pwd  = ""; 
    $sql_db = "opensos";  

    // Connect to the database
    $db_conn = @mysqli_connect($host,$user,$pwd,$sql_db);

    // Error message if the connection does not work
    if (!$db_conn)
    {
        die("Connection Failed: " . mysqli_connect_error());
    }
?>
