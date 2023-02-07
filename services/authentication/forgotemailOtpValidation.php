<?php

include "../config.php";
include "../env.php";
include "../utils/dateFunctions.php";

$userOtp = $_GET["otp"];
$email = $_GET["email"];

$finalObject =  new \stdClass();



try {
    $sql = "select verification_code,last_requested_at from forgot_verification where user_id = (SELECT user_id FROM users where user_email='" . $email . "')";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) != 0) {
        $row = mysqli_fetch_row($result);

        $verificationCode = $row[0];
        $requestedTime = $row[1];

        if (getTimeDifference($requestedTime) < $otpValidTime) {
            if ($verificationCode == $userOtp) {
                session_start();
                $_SESSION["isChangePasswordValid"] = "true";
                $finalObject->status = "success";
            } else {
                $finalObject->status = "error";
                $finalObject->message = "invalid OTP!";
            }
        } else {
            $finalObject->status = "error";
            $finalObject->message = "OTP expired!";
        }
    }
    mysqli_close($con);
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1001";
}
$response = json_encode($finalObject);
echo $response;
