<?php

$servername = ""; // Add your database server name here
$username = ""; // Add your database user name here
$password = ""; // Add your database password here
$dbname = ""; // Add your database name here

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error)
{   
    die("Connection failed: " . $conn->connect_error);
}

?>