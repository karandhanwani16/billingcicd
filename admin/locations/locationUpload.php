<?php

include("../services/urlValidation.php");
include("../services/config.php");
include("../services/helperFunctions.php");
// session_start();
// checkUrlValidation("admin", "../login.php");
// $user_id = $_SESSION["user_id"];

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Upload</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
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

    <div class="title">Locations</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row">
            <div class="inp-group">
                <div class="inp-label">Location name</div>
                <input type="text" value="" class="inp required" id="txtlocationname" placeholder="Location Name" data-id="txtlocationname" />
                <div class="error-text" data-id="txtlocationname">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Location Active</div>
                <select id="ddllocationactive" class="ddl required" data-id="ddllocationactive">
                    <option value="">Select Location Active</option>
                    <option value="true" selected>Yes</option>
                    <option value="false">No</option>
                </select>
                <div class="error-text" data-id="ddllocationactive">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->
        <div class="btn-row">
            <div class="primary-btn btn f-center submit--btn">Submit</div>
        </div>
    </form>

    <script src="../scripts/helperFunctions.js"></script>
    <script>
        let inputs = document.querySelectorAll("input.required");
        let dropdowns = document.querySelectorAll("select.required");
        let errorTexts = document.querySelectorAll(".error-text");
    </script>
    <!-- submitting data -->
    <script>
        let locationObject = {
            "name": document.getElementById("txtlocationname").value,
            "active": document.getElementById("ddllocationactive").value
        };
        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            //if form is valid
            if (isValid()) {

                locationObject.name = document.getElementById("txtlocationname").value;
                locationObject.active = document.getElementById("ddllocationactive").value;


                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingState(submitBtn);
                        //console.log(this.responseText);
                        showAlert(result.message, result.status);
                        refreshPage();
                    }
                };
                addLoadingState(submitBtn);


                xmlhttp.open("POST", `services/uploadLocation.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("data=" + JSON.stringify(locationObject));
                //showAlert("Category Uploaded Succesfully","success");   
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