<?php
$servername = "localhost";
$username = "aarele_root";
$password = "O67id5t1!";
$dbname = "aarele_database";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
} else {
    "Успех";
}
?>
