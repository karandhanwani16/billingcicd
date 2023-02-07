<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yash Enterprises | Login</title>
    <link rel="stylesheet" href="style/assets.css">
    <link rel="stylesheet" href="style/login.css">

</head>

<body>
    <div class="main--cont">
        <div class="alert--cont"></div>
        <div class="heading">Login</div>

        <form action="services/authentication/signup.php" method="POST" onsubmit="return(isValid());">

            <div class="inp-group">
                <div class="inp-label">E-mail address</div>
                <input type="email" class="inp login--email" name="txtemail" placeholder="Example@gmail.com" data-id="txtemail">
                <div class="error-text" data-id="txtemail">Cannot leave this field blank</div>
            </div>

            <div class="inp-group">
                <div class="inp-label">Password</div>
                <div class="password-cont">
                    <input type="password" data-id="txtpassword" name="txtpassword" class="inp password" placeholder="Password" />
                    <img src="assets/icons/show-password.svg" class="password-toggle" data-type="login" alt="">
                </div>
                <div class="error-text" data-id="txtpassword">Cannot leave this field blank</div>
            </div>
            <a href="forgotpassword.html" class="forgot--password">forgot password?</a>
            <div class="submit-btn btn primary-btn f-center">Login</div>
        </form>
    </div>

    <script src="admin/scripts/helperFunctions.js"></script>
    <script src="scripts/form/passwordtoggle.js"></script>
    <script src="scripts/form/loginvalidaion.js"></script>
    <script>
        document.addEventListener("keyup", e => {
            var code = e.keyCode || e.which;
            if (code === 13) {
                loginBtn.click();
            }
        });
    </script>

</body>

</html>