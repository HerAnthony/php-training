<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$servername="localhost";
$username = "root";
$password = "secret";
//create connection
$conn = new mysqli($servername,$username,$password);
//check connection fam, that arrow -> says inside of conn object
if ($conn->connect_error) {
  die("No worky " . $conn->connect_error);
} else {
  $success = "It worked!";
}
