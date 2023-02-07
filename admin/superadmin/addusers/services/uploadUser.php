<?php

include "../../../services/config.php";
include "../../../services/helperFunctions.php";
include "../../../../services/env.php";

$data = $_POST["data"];
$data =  json_decode($data, true);

$finalObject =  new \stdClass();

//Step 1 : getting all the variables

$email = $data["email"];
$fname = $data["fname"];
$lname = $data["lname"];
$type = $data["type"];
//upload all the images

$currentTimeStamp = getCurrentTimestamp();
//upload all the images

// $normalPassword = "admin";
$normalPassword = generateRandomPassword(8);
$password = password_hash($normalPassword, PASSWORD_BCRYPT);

try {
    if (!isUserExist($email, $con)) {
        $maxUserId = getCurrentId("user_id", "users", $con);
        $sql = "insert into users values(" . $maxUserId . ",'" . $email . "','" . $password . "','" . $fname . "','" . $lname . "','" . $type . "','" . $currentTimeStamp . "')";
        if (mysqli_query($con, $sql)) {
            $subject = "Welcome to Yash Enterprises";
            $finalPasswordMessage = "<h1>Welcome to Yash Enterprises</h1></br><p>The credentials for your account is :</p></br><strong>Email: </strong>" . $email . " </br> <strong>Password: </strong>" . $normalPassword;
            if (sendEmailHtml($email, $subject, $finalPasswordMessage, $billingMainEmail, $companyName)) {
                $finalObject->status = "success";
                $finalObject->message = "User created successfully!!!";
            } else {
                $finalObject->status = "error";
                $finalObject->message = "Error #1002";
            }
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Error #1001";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "User Already Exists!!!";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}


$response = json_encode($finalObject);
echo $response;



function generateRandomPassword($password_length)
{
    $randomPassword = "";
    $availableCharacters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890.,<>/\}]{[!@#$%^&*()_-=+";
    for ($i = 0; $i < $password_length; $i++) {
        $n = rand(0, strlen($availableCharacters) - 1);
        $randomPassword .= $availableCharacters[$n];
    }
    return $randomPassword;
}



function isUserExist($email, $con)
{
    $isExist = true;
    $sql = "select * from users where user_email='" . $email . "'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) == 0) {
        $isExist = false;
    }
    return $isExist;
}
