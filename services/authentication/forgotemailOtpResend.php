<?php

$email = $_GET["email"];


include "../env.php";
include "../config.php";
include "../utils/dateFunctions.php";
include "../utils/generalFunctions.php";


try {
    //check if the email exist
    $otp = generateOtp(6);
    $table_name = "forgot_verification";
    if (emailExist($email, $con, $table_name) == 1) {
        //generate code and update the table
        if (isEmailAlreadySent($email, $con, $otpValidTime, $table_name)) {
            echo 1;
        } else {
            if (updateVerificationTable($otp, $email, $con, $table_name)) {
                $finalEmailMessage =  $baseOtpMessage . $otp;
                sendEmail($email, "Forgot password email OTP", $finalEmailMessage);
                echo 1;
            } else {
                echo 0;
            }
        }
    } else {
        // insert into the forgotpassword table
        if (insertForgotVerificationTable($otp, $email, $con)) {
            $finalEmailMessage =  $baseOtpMessage . $otp;
            sendEmail($email, "Forgot password email OTP", $finalEmailMessage);
            echo 1;
        } else {
            echo 0;
        }
    }
} catch (Exception $e) {
    echo $e;
}
