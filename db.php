<?php
$host = "localhost";
$user = "root"; // Change if necessary
$pass = ""; // Change if necessary
$dbname = "geo_location";

$conn = new mysqli($host, $user, $pass, $dbname);
$conn->query("SET time_zone = '+08:00'");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
