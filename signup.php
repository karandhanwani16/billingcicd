<?php

include("services/config.php");
include("services/utils/dateFunctions.php");

$first_name = "Sandesh";
$last_name = "sir";
$email = "sandesh@yashconsumer.co.in";
$password = "Sandesh@123";

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$currentTimeStamp = getCurrentTimestamp();

$sql = "insert into users values(11,'" . $email . "','" . $hashedPassword . "','" . $first_name . "','" . $last_name . "','superadmin','" . $currentTimeStamp . "')";
echo $sql;
if (mysqli_query($con, $sql)) {
    echo "Done !!!";
} else {
    echo "Not Done !!!";
}
