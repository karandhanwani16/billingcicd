<?php

$email = $_GET["email"];
$admin = "1";

session_start();
if (isset($_SESSION["isChangePasswordValid"])) {
    if ($_SESSION["isChangePasswordValid"] != "true") {
        echo "redirect";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yash Enterprises | Change Password</title>
    <link rel="stylesheet" href="style/assets.css">
    <link rel="stylesheet" href="style/login.css">
    <link rel="stylesheet" href="style/emailverification.css">
    <script src="scripts/form/helperFunctions.js"></script>
    <style>
        .main--cont {
            width: 45vw;
            height: 90vh;
            overflow-y: auto;
        }

        .illustration-cont {
            width: 100%;
        }

        .illustration-cont img {
            height: 25vh;
            margin-bottom: 16px;
        }
    </style>

</head>

<body>
    <div class="main--cont">
        <div class="heading">Change Password</div>

        <div class="illustration-cont f-center">
            <img src="assets/icons/forgot-password-illustration.svg" alt="">
        </div>
        <form>
            <div class="inp-group">
                <div class="inp-label">Password</div>
                <div class="password-cont">
                    <input type="password" data-id="txtpassword" name="txtpassword" class="inp password" placeholder="Password" />
                    <img src="assets/icons/show-password.svg" class="password-toggle" data-type="signup" alt="">
                </div>
                <div class="error-text" data-id="txtpassword">Cannot leave this field blank</div>
            </div>
            <div class="error-messages-cont">

            </div>

            <div class="inp-group">
                <div class="inp-label">Confirm Password</div>
                <input type="password" name="txtconfirmpassword" data-id="txtconfirmpassword" class="inp confirmpassword" placeholder="Confirm Password" />
                <div class="error-text" id="txtconfirmpassword" data-id="txtconfirmpassword">Cannot leave this field blank</div>
            </div>

            <div class="submit-btn btn primary-btn f-center">Change password</div>
        </form>
    </div>
    <script src="admin/scripts/helperFunctions.js"></script>
    <script src="scripts/form/passwordtoggle.js"></script>

    <script>
        let inputs = document.querySelectorAll("input");
        let errorTexts = document.querySelectorAll(".error-text");
        let passwordInput = document.querySelector(".password");
        let confirmPasswordInput = document.querySelector(".confirmpassword");

        let submitBtn = document.querySelector(".submit-btn");

        submitBtn.addEventListener("click", e => {
            if (isValid()) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        if (result.status == "success") {
                            window.location = "passwordchangesuccessful.html";
                        } else {
                            showAlert(result.message, result.status);
                        }
                    }
                };
                xhttp.open("POST", "services/forgotpassword/changepassword.php", true);
                xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhttp.send("password=" + passwordInput.value + "&email=<?php echo $email; ?>");
            }
            // window.location = "passwordchangesuccessful.html";
        });





        function isValid() {
            isValidResult = true;

            inputs.forEach(input => {
                if (input.type != "radio") {

                    let isFilled = requiredValidator(input);
                    let elementId = input.attributes["data-id"].value;
                    if (!isFilled) {
                        getCorrespondingErrorText(errorTexts, elementId, errorMessages.blankMessage);
                        input.focus();
                        input.classList.add("error-inp");
                        isValidResult = false;
                    } else {
                        hideErrorText(errorTexts, elementId);
                        input.classList.remove("error-inp");
                    }
                }
            });

            if (passwordInput.value != confirmPasswordInput.value) {
                confirmPasswordInput.focus();
                document.getElementById("txtconfirmpassword").innerHTML = "Passwords must match";
                document.getElementById("txtconfirmpassword").style.display = "block";
                isValidResult = false;
            } else {
                document.getElementById("txtconfirmpassword").style.display = "none";
            }


            let passwordValidation = validatePassword(passwordInput.value);
            if (!passwordValidation.isValid) {
                passwordInput.focus();
                isValidResult = false;
            }
            return isValidResult;
        }

        let errorMessagesCont = document.querySelector(".error-messages-cont");

        passwordInput.addEventListener("keyup", e => {
            let input_value = passwordInput.value;
            if (input_value != "") {
                let passwordValidation = validatePassword(input_value);
                if (passwordValidation.isValid) {
                    errorMessagesCont.style.display = "none";
                    passwordInput.classList.add("validated-inp");
                    passwordInput.classList.remove("error-inp");
                } else {
                    errorMessagesCont.style.display = "block";

                    errorMessagesCont.innerHTML = "";
                    for (var i = 0; i < passwordValidation.passwordErrors.length; i++) {
                        errorMessagesCont.innerHTML += "<div class='error'>" + passwordValidation.passwordErrors[i] + "</div>";
                    }

                    passwordInput.classList.add("error-inp");
                    passwordInput.classList.remove("validated-inp");
                }
            }
        });
    </script>
</body>

</html>