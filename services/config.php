<?php

include("env.php");

$con = mysqli_connect($HOST, $USER, $PASSWORD, $DATABASE);

// Check connection
if (mysqli_connect_errno()) {
  echo "<script>alert('Failed to connect to MySQL: " . $mysqli->connect_error . "')</script>";
  exit();
}
