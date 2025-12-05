<?php
$host = "shuttle.proxy.rlwy.net";
$port = 57010;
$username = "root";
$password = "oiivyqPEmaBOEoDTRTctCCYatMaoiNCM";
$dbname = "railway";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>