<?php
$env = "dev";
// $env = "qa";
// $env = "prod";

$HOST = "";
$USER = "";
$PASSWORD = "";
$DATABASE = "";


if ($env == "dev") {
  $HOST = "mysql-service";
  $USER = "root";
  $PASSWORD = "";
  $DATABASE = "billingsystem";
} elseif ($env == "qa") {
  $HOST = "sql105.epizy.com";
  $USER = "epiz_25937302";
  $PASSWORD = "LwdAb5QCwjI";
  $DATABASE = "epiz_25937302_billingsystem";
} else {
  $HOST = "localhost";
  $USER = "yashcons_admin";
  $PASSWORD = "Y0{9,_NQ?WBc";
  $DATABASE = "yashcons_main";
}


$con = mysqli_connect($HOST, $USER, $PASSWORD, $DATABASE);

// Check connection
if (mysqli_connect_errno()) {
  echo "<script>alert('Failed to connect to MySQL: " . $mysqli->connect_error . "')</script>";
  exit();
}
