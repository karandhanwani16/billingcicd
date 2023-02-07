<?php

include("../services/urlValidation.php");
include("../services/config.php");
include("../services/helperFunctions.php");
// session_start();
// checkUrlValidation("admin", "../login.php");
// $user_id = $_SESSION["user_id"];

$firmId = $_GET["id"];

$firmDetails = getFirmDetails($firmId, $con);

function getFirmDetails($firm_id, $con)
{
    $output = new \stdClass();
    $query = "select * from firm where firm_id = " . $firm_id;
    $result = $con->query($query);
    if ($result->num_rows != 0) {
        $row = $result->fetch_assoc();
        $output = $row;
    }
    return $output;
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firm Upload</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
    <link rel="stylesheet" href="../style/sales.css">
    <!-- <link rel="stylesheet" href="../style/table.css"> -->
    <style>
        .hidden-row {
            display: none;
        }

        tbody,
        tfoot {
            padding: 12px 0;
        }

        tfoot tr td input {
            width: 90% !important;
        }

        tbody {
            height: 10vh;
            overflow-y: scroll !important;
        }
    </style>
</head>

<body>

    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Firms</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row">
            <div class="inp-group">
                <div class="inp-label">Firm Name</div>
                <input type="text" value="<?php echo $firmDetails["firm_name"] != "" ? $firmDetails["firm_name"] : ""; ?>" class="inp required" id="txtfirmname" placeholder="Firm Name" data-id="txtfirmname" />
                <div class="error-text" data-id="txtfirmname">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Firm GST Number</div>
                <input type="text" value="<?php echo $firmDetails["firm_gst"] != "" ? $firmDetails["firm_gst"] : ""; ?>" class="inp required" id="txtfirmgstno" placeholder="Firm GST Number" data-id="txtfirmgstno" />
                <div class="error-text" data-id="txtfirmgstno">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->
        <div class="inp-row">
            <div class="inp-group">
                <div class="inp-label">Firm Address</div>
                <input type="text" value="<?php echo $firmDetails["firm_address"] != "" ? $firmDetails["firm_address"] : ""; ?>" class="inp required" id="txtfirmaddress" placeholder="Firm Address" data-id="txtfirmaddress" />
                <div class="error-text" data-id="txtfirmaddress">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Firm State</div>
                <input type="text" value="<?php echo $firmDetails["firm_state"] != "" ? $firmDetails["firm_state"] : ""; ?>" class="inp required" id="txtfirmstate" placeholder="Firm State" data-id="txtfirmstate" />
                <div class="error-text" data-id="txtfirmstate">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->
        <div class="inp-row">
            <div class="inp-group">
                <div class="inp-label">Firm State Code</div>
                <input type="text" value="<?php echo $firmDetails["firm_state_code"] != "" ? $firmDetails["firm_state_code"] : ""; ?>" class="inp required" id="txtfirmstatecode" placeholder="Firm State Code" data-id="txtfirmstatecode" />
                <div class="error-text" data-id="txtfirmstatecode">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->

        <div class="table-row">
            <table>
                <thead>
                    <tr>
                        <th>Sr no.</th>
                        <th>Bank Name</th>
                        <th>Account No.</th>
                        <th>Branch Name</th>
                        <th>IFSC Code.</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="firmBankTable">
                </tbody>
                <tfoot>
                    <tr style="color: #aaa;">
                        <td></td>
                        <td>
                            <input type="txt" id="txtbankname" placeholder="Bank Name" class="inp" />
                        </td>
                        <td>
                            <input type="txt" id="txtaccountno" placeholder="Account No" class="inp" />
                        </td>
                        <td>
                            <input type="txt" id="txtbranchname" placeholder="Branch Name" class="inp" />
                        </td>
                        <td>
                            <input type="txt" id="txtifsc" placeholder="IFSC Code" class="inp" />
                        </td>
                        <td rowspan="">
                            <div class="add-row">Add</div>
                        </td>

                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="btn-row">
            <div class="primary-btn btn f-center submit--btn">Submit</div>
        </div>
    </form>

    <script src="../scripts/helperFunctions.js"></script>

    <!-- handling banks start -->
    <script src="./scripts/bankTable.js"></script>
    <script src="./scripts/handleBankTable.js"></script>
    <script>
        <?php
        $query = "select * from firm_bank where firm_id = " . $firmId;
        $result = $con->query($query);
        if ($result->num_rows != 0) {
            while ($row = $result->fetch_assoc()) {
                echo "banktable.insertRow('" . $row["firm_bank_name"] . "','" . $row["firm_bank_account_no"] . "','" . $row["firm_bank_branch_name"] . "','" . $row["firm_bank_ifsc"] . "');";
            }
            echo "refreshView();";
        }
        ?>
    </script>
    <!-- handling banks end -->

    <script>
        let inputs = document.querySelectorAll("input.required");
        let dropdowns = document.querySelectorAll("select.required");
        let errorTexts = document.querySelectorAll(".error-text");
    </script>
    <!-- submitting data -->
    <script>
        let firmObject = {
            "name": document.getElementById("txtfirmname").value,
            "gst": document.getElementById("txtfirmgstno").value,
            "address": document.getElementById("txtfirmaddress").value,
            "state": document.getElementById("txtfirmstate").value,
            "statecode": document.getElementById("txtfirmstatecode").value,
            "banks": []
        };
        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {

            // if therea are bank details
            if (banktable.bankRows.length > 0) {
                //if form is valid
                if (isValid()) {
                    firmObject.name = document.getElementById("txtfirmname").value;
                    firmObject.gst = document.getElementById("txtfirmgstno").value;
                    firmObject.address = document.getElementById("txtfirmaddress").value;
                    firmObject.state = document.getElementById("txtfirmstate").value;
                    firmObject.statecode = document.getElementById("txtfirmstatecode").value;
                    firmObject.banks = banktable.bankRows;
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
                    xmlhttp.open("POST", `services/updateFirm.php`, true);
                    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xmlhttp.send("id=<?php echo $firmId; ?>&data=" + JSON.stringify(firmObject));
                }
            } else {
                showAlert("Please Enter Bank Details !!", "warning");

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