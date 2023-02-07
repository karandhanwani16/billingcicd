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
    <title>Buyer Upload</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
</head>

<body>

    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Buyers</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row adj-row">
            <div class="inp-group">
                <div class="inp-label">Buyer Name</div>
                <input type="text" value="" class="inp required" id="txtsuppliername" placeholder="Buyer Name" data-id="txtsuppliername" />
                <div class="error-text" data-id="txtsuppliername">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Buyer's Firm</div>
                <select id="ddlfirm" class="ddl required" data-id="ddlfirm">
                    <option value="">Select Buyer's Firm</option>
                </select>
                <div class="error-text" data-id="ddlfirm">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
        </div>
        <!-- input row end -->
        <div class="inp-row">
            <div class="inp-group">
                <div class="inp-label">Buyer Address</div>
                <input type="text" value="" class="inp required" id="txtsupplieraddress" placeholder="Buyer Address" data-id="txtsupplieraddress" />
                <div class="error-text" data-id="txtsupplieraddress">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Buyer GST No.</div>
                <input type="text" value="" class="inp required" id="txtsuppliergstno" placeholder="Buyer GST No." data-id="txtsuppliergstno" />
                <div class="error-text" data-id="txtsuppliergstno">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->
        <div class="inp-row">
            <div class="inp-group">
                <div class="inp-label">Buyer State</div>
                <input type="text" value="" class="inp required" id="txtsupplierstate" placeholder="Buyer State" data-id="txtsupplierstate" />
                <div class="error-text" data-id="txtsupplierstate">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Buyer State Code</div>
                <input type="text" value="" class="inp required" id="txtsupplierstatecode" placeholder="Buyer State Code" data-id="txtsupplierstatecode" />
                <div class="error-text" data-id="txtsupplierstatecode">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->
        <div class="inp-row">
            <div class="inp-group">
                <div class="inp-label">Buyer HSN Code</div>
                <input type="text" value="" class="inp required" id="txtsupplierhsncode" placeholder="Buyer HSN Code" data-id="txtsupplierhsncode" />
                <div class="error-text" data-id="txtsupplierhsncode">Cannot leave this field blank</div>
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
        let inputs = document.querySelectorAll("form input.required");
        let dropdowns = document.querySelectorAll("form select.required");
        let errorTexts = document.querySelectorAll("form .error-text");
    </script>
    <!-- submitting data -->
    <script>
        let supplierObject = {
            "name": document.getElementById("txtsuppliername").value,
            "firm": document.getElementById("ddlfirm").value,
            "address": document.getElementById("txtsupplieraddress").value,
            "gst": document.getElementById("txtsuppliergstno").value,
            "state": document.getElementById("txtsupplierstate").value,
            "hsn": document.getElementById("txtsupplierhsncode").value,
            "statecode": document.getElementById("txtsupplierstatecode").value
        };

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


        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            //if form is valid
            if (isValid()) {

                supplierObject.name = escape(document.getElementById("txtsuppliername").value);
                supplierObject.firm = document.getElementById("ddlfirm").value;
                supplierObject.address = escape(document.getElementById("txtsupplieraddress").value);
                supplierObject.gst = document.getElementById("txtsuppliergstno").value;
                supplierObject.state = document.getElementById("txtsupplierstate").value;
                supplierObject.hsn = document.getElementById("txtsupplierhsncode").value;
                supplierObject.statecode = document.getElementById("txtsupplierstatecode").value;
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
                xmlhttp.open("POST", `services/uploadSupplier.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("data=" + JSON.stringify(supplierObject));
            }

        });
    </script>
    <!-- handle new Firm -->
    <!-- new group entry handle -->
    <script>
        window.onload = function exampleFunction() {
            getNewData();
        }

        function getNewData() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    loadDataIntoDdl(result);
                }
            };
            xmlhttp.open("POST", `services/getNewData.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send();
        }

        function loadDataIntoDdl(data) {
            let ddl = document.querySelector('#ddlfirm');
            ddl.innerHTML = "<option value=''>Select Firm</option>";
            data.forEach(entry => {
                ddl.innerHTML += `<option value="${entry[0]}">${entry[1]}</option>`;
            });
        }
    </script>
</body>

</html>