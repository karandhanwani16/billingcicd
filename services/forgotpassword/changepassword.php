<?php
include("../config.php");
include("../utils/generalFunctions.php");
$email = $_POST["email"];
$password = $_POST["password"];

$finalObject = new \stdClass();

try {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $query = "update users set user_password='" . $hashedPassword . "' where user_email='" . $email . "'";
    if (mysqli_query($con, $query)) {
        if (sendEmailHtml($email, "Yash Enterprises Password Change Notification", "Hello User,<br/> Password for your yash enterprises Account<br/> Email: " . $email . " has been changed now. <br/> Thankyou.")) {
            if (isset($_SESSION['isChangePasswordValid'])) {
                session_unset($_SESSION['isChangePasswordValid']);
            }
            $finalObject->status = "success";
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Cannot send Email !!";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Cannot update password!!";
    }
    mysqli_close($con);
} catch (exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1001";
}

$response = json_encode($finalObject);
echo $response;
