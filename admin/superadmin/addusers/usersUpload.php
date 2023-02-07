<?php

include("../../services/urlValidation.php");
include("../../services/config.php");
include("../../services/helperFunctions.php");
// session_start();
// checkUrlValidation("admin", "../../login.php");
// $user_id = $_SESSION["user_id"];
// $shop_id = getShopId($user_id, $con);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Upload</title>
    <link rel="stylesheet" href="../../style/assets.css">
    <link rel="stylesheet" href="../../style/forms.css">
    <style>
        .hidden-row {
            display: none;
        }
    </style>
</head>

<body>

    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Users</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row">
            <div class="inp-group">
                <div class="inp-label">User Email</div>
                <input type="text" class="inp required" id="txtuseremail" placeholder="User Email" data-id="txtuseremail" />
                <div class="error-text" data-id="txtuseremail">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">User First Name</div>
                <input type="text" class="inp required" id="txtuserfirstname" placeholder="User First Name" data-id="txtuserfirstname" />
                <div class="error-text" data-id="txtuserfirstname">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
        </div>
        <!-- input row end -->
        <div class="inp-row">
            <div class="inp-group">
                <div class="inp-label">User Last Name</div>
                <input type="text" class="inp required" id="txtuserlastname" placeholder="User Last Name" data-id="txtuserlastname" />
                <div class="error-text" data-id="txtuserlastname">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

            <div class="inp-group">
                <div class="inp-label">User Type</div>
                <select id="ddlusertype" class="ddl required" data-id="ddlusertype">
                    <option value="">Select User Type</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="salescord">Billing Executive</option>
                </select>
                <div class="error-text" data-id="ddlusertype">Cannot leave this field blank</div> <!-- inp group end -->
            </div>
        </div>
        <!-- input row end -->
        <div class="btn-row">
            <div class="primary-btn btn f-center submit--btn">Submit</div>
        </div>
    </form>

    <script src="../../scripts/helperFunctions.js"></script>
    <script>
        let inputs = document.querySelectorAll("input.required");
        let dropdowns = document.querySelectorAll("select.required");
        let errorTexts = document.querySelectorAll(".error-text");
    </script>
    <!-- submitting data -->
    <script>
        let userObject = {
            "email": document.getElementById("txtuseremail").value,
            "fname": document.getElementById("txtuserfirstname").value,
            "lname": document.getElementById("txtuserlastname").value,
            "type": document.getElementById("ddlusertype").value
        };
        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            //if form is valid
            if (isValid()) {

                userObject.email = document.getElementById("txtuseremail").value;
                userObject.fname = document.getElementById("txtuserfirstname").value;
                userObject.lname = document.getElementById("txtuserlastname").value;
                userObject.type = document.getElementById("ddlusertype").value;

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingState(submitBtn);
                        showAlert(result.message, result.status);
                        refreshPage();
                    }
                };
                addLoadingState(submitBtn);
                xmlhttp.open("POST", `services/uploadUser.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("data=" + JSON.stringify(userObject));
            }

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
            dropdowns.forEach(dropdown => {
                let isFilled = requiredValidator(dropdown);
                let elementId = dropdown.attributes["data-id"].value;
                if (!isFilled) {
                    getCorrespondingErrorText(errorTexts, elementId, errorMessages.blankMessage);
                    dropdown.focus();
                    dropdown.classList.add("error-inp");
                    isValidResult = false;
                } else {
                    hideErrorText(errorTexts, elementId);
                    dropdown.classList.remove("error-inp");
                }
            });
            return isValidResult;
        }
    </script>
</body>

</html>