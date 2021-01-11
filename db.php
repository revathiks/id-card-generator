<?php

$servername = "172.16.5.251";
$username = "cmroot";
$password = "";
$dbname="idg_demo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
?> 