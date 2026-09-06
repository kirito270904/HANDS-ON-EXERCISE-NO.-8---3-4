<?php
/**
 * Database connection
 * PHP Output #3 & #4 - Saint Michael College of Caraga
 */

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "registration_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
