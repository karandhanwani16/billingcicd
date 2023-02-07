<?php

include("../config.php");
include("../env.php");
include("../utils/generalFunctions.php");

$finalObject =  new \stdClass();

$email = $_POST["email"];
$baseOtpMessage = "The OTP to reset your password is:";

$isEmailSent = sendEmailVerification($email, $baseOtpMessage, $con, $otpValidTime, "forgot_verification");
if ($isEmailSent->status == "success") {
    $finalObject->status = "success";
} else {
    $finalObject = $isEmailSent;
}

function sendEmailVerification($email, $baseOtpMessage, $con, $otpValidTime, $table_name)
{
    $resultObject = new \stdClass();
    $otp = generateOtp(6);
    if (emailExist($email, $con, $table_name) == 1) {
        //generate code and update the table
        if (isEmailAlreadySent($email, $con, $otpValidTime, $table_name)) {
            $resultObject->status = "success";
        } else {
            if (updateVerificationTable($otp, $email, $con, $table_name)) {
                $finalEmailMessage =  $baseOtpMessage . $otp;
                sendEmail($email, "Forgot password email OTP", $finalEmailMessage);
                $resultObject->status = "success";
            } else {
                $resultObject->status = "error";
                $resultObject->message = "Error #1001";
            }
        }
    } else {
        // insert into the forgotpassword table
        if (insertForgotVerificationTable($otp, $email, $con)) {
            $finalEmailMessage =  $baseOtpMessage . $otp;
            sendEmail($email, "Forgot password email OTP", $finalEmailMessage);
            $resultObject->status = "success";
        } else {
            $resultObject->status = "error";
            $resultObject->message = "Error #1001";
        }
    }
    return $resultObject;
}



$response = json_encode($finalObject);
echo $response;
