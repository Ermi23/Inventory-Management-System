<?php
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "cake_and_beakery";

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);
if($conn->connect_error) {
 die("connection failed");
}
?>