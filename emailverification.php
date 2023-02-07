<?php
$email = $_GET["email"];
$phone = $_GET["phone"];
//get the details of the phone verification

include "services/config.php";
include "services/env.php";
include "services/utils/dateFunctions.php";

try {
    $sql = "select email_confirmed from users where user_email='" . $email . "'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) != 0) {
        $row = mysqli_fetch_row($result);
        if ($row[0] == "true") {
            header("Location: phoneverification.php?phone=" . $phone);
        }
    }

    $sql = "select last_requested_at from email_verification where user_id = (SELECT user_id FROM users where user_email='" . $email . "')";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) != 0) {
        $row = mysqli_fetch_row($result);
        $requestedTime = $row[0];
        $endTime = strtotime("+" . $otpValidTime . " minutes", strtotime($requestedTime));
        $endTime = date('Y-m-d G:i:s', $endTime);
    }
    mysqli_close($con);
} catch (Exception $e) {
    echo "<script>alert('" . $e->getMessage() . "')</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INKT | Email Verification</title>
    <link rel="stylesheet" href="style/assets.css">
    <link rel="stylesheet" href="style/signup.css">
    <link rel="stylesheet" href="style/emailverification.css">
    <script src="scripts/form/helperFunctions.js"></script>
</head>

<body>
    <a class="close-btn" href="index.php">
        <img src="assets/icons/close-btn.svg" alt="">
    </a>
    <div class="heading">One Time Password</div>

    <div class="illustration-cont">
        <img src="assets/icons/email-otp-illustration.svg" alt="">
    </div>

    <p class="text">E-mail has been sent to <br><strong id="email"><?php echo $email; ?></strong></p>

    <p class="text">Enter the 6 digit OTP in your email to confirm your E-mail address</p>

    <form>
        <div class="sub-heading">One Time Password (OTP)</div>

        <div class="row otp-row">
            <input type="tel" data-id="0" data-id="0" maxlength="1" placeholder="0" onkeypress="return isNumber(event)" class="otp-inp">
            <input type="tel" data-id="1" maxlength="1" placeholder="0" onkeypress="return isNumber(event)" class="otp-inp">
            <input type="tel" data-id="2" maxlength="1" placeholder="0" onkeypress="return isNumber(event)" class="otp-inp">
            <input type="tel" data-id="3" maxlength="1" placeholder="0" onkeypress="return isNumber(event)" class="otp-inp">
            <input type="tel" data-id="4" maxlength="1" placeholder="0" onkeypress="return isNumber(event)" class="otp-inp">
            <input type="tel" data-id="5" maxlength="1" placeholder="0" onkeypress="return isNumber(event)" class="otp-inp">
        </div>
        <div class="submit-btn btn primary-btn f-center">Confirm Email</div>
        <div class="resend-btn btn secondary-btn f-center">Resend OTP</div>
    </form>

    <div class="timer-text"></div>

    <script>
        var countDownDate = new Date("<?php echo $endTime; ?>").getTime();
        let selectedEmail = "<?php echo $email; ?>";
        let mainPhoneVariable = "<?php echo $phone; ?>";
    </script>
    <script src="scripts/otp/emailconfirmation.js"></script>
</body>

</html>