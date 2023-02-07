<?php

include("../services/urlValidation.php");
include("../services/config.php");
include("../services/helperFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];


$debitnoteCopy = isset($_GET["id"]);
$curentId = 0;

if ($debitnoteCopy) {
    $curentId = $_GET["id"];
    $debitnoteDetails = getTableDetails("debit_note", "debit_note_id", $curentId, $con);
}


function getTableDetails($tableName, $primaryIdColumnName, $primaryId, $con)
{
    $details =  new \stdClass();
    $query = "select * from " . $tableName . " where " . $primaryIdColumnName . " = " . $primaryId;
    $result = $con->query($query);
    $countOfRows = $result->num_rows;

    if ($countOfRows != 0) {
        $row = $result->fetch_assoc();
        $details = $row;
    }
    return $details;
}
date_default_timezone_set('Asia/Kolkata');
$currentDate = date('Y-m-d');


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debit Note Upload</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
    <link rel="stylesheet" href="../style/sales.css">
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

        .back-btn-cont {
            display: inline-flex;
            padding: 12px;
            background-color: #f6f5f4;
            border-radius: 100%;
            margin-top: 16px !important;
        }

        .back-btn-cont:hover {
            cursor: pointer;
            opacity: 0.9;
        }

        .table-row ul {
            list-style-type: none;
        }
    </style>
</head>

<body>

    <?php
    if ($debitnoteCopy) {
        echo "<a onclick='history.back()' class='back-btn-cont'><img src='../assets/icons/back.svg' alt='back-btn'></a>";
    }
    ?>


    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Debit Note</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row adj-row row-5">
            <div class="inp-group">
                <div class="inp-label">Debit Note's Firm</div>
                <select id="ddlfirm" class="ddl required" data-id="ddlfirm">
                    <option value="">Select Debit Note's Firm</option>
                </select>
                <div class="error-text" data-id="ddlfirm">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Bank</div>
                <select id="ddlbank" class="ddl required disabled" data-id="ddlbank">
                    <option value="">Select Bank</option>
                </select>
                <div class="error-text" data-id="ddlbank">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Supplier</div>
                <select id="ddlsupplier" class="ddl required disabled" data-id="ddlsupplier">
                    <option value="">Select Supplier</option>
                </select>
                <div class="error-text" data-id="ddlsupplier">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <!-- </div> -->
            <div class="inp-group">
                <div class="inp-label">Debit Note Date</div>
                <input type="date" value="<?php echo $currentDate; ?>" class="inp required" id="txtdebitnotedate" data-id="txtdebitnotedate" />
                <div class="error-text" data-id="txtdebitnotedate">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Debit Note PO No.</div>
                <input type="text" value="<?php echo  $debitnoteCopy ? $debitnoteDetails["debit_note_other_ref"] : ""; ?>" class="inp required" id="txtdebitnoteotherreference" placeholder="Debit Note PO No." data-id="txtdebitnoteotherreference" />
                <div class="error-text" data-id="txtdebitnoteotherreference">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->
        <div class="inp-row adj-row row-5">
            <div class="inp-group">
                <div class="inp-label">Debit Note No.</div>
                <input type="text" value="" class="inp required <?php echo $user_type != "super_admin" ? "disabled" : ""; ?>" tabindex="-1" id="txtdebitnoteno" placeholder="Debit Note No." data-id="txtdebitnoteno" />
                <div class="error-text" data-id="txtdebitnoteno">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">GST Percentage</div>
                <input type="text" value="<?php echo  $debitnoteCopy ? (strtolower($debitnoteDetails["debit_note_place_of_supply"]) == "maharashtra" ? $debitnoteDetails["debit_note_sgst_percentage"] * 2 : $debitnoteDetails["debit_note_igst_percentage"]) : ""; ?>" class="inp required" id="txtgst" placeholder="GST Percentage" data-id="txtgst" />
                <div class="error-text" data-id="txtgst">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">SGST</div>
                <input type="text" class="inp disabled" id="txtsgst" tabindex="-1" placeholder="SGST" data-id="txtsgst" />
                <div class="error-text" data-id="txtsgst">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">CGST</div>
                <input type="text" class="inp disabled" id="txtcgst" tabindex="-1" placeholder="CGST" data-id="txtcgst" />
                <div class="error-text" data-id="txtcgst">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">IGST</div>
                <input type="text" class="inp disabled" id="txtigst" tabindex="-1" placeholder="IGST" data-id="txtigst" />
                <div class="error-text" data-id="txtigst">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->
        <div class="inp-row adj-row row-5" style="justify-content: flex-start;">
            <div class="inp-group">
                <div class="inp-label">Place of Supply</div>
                <input type="text" value="<?php echo  $debitnoteCopy ? $debitnoteDetails["debit_note_place_of_supply"] : "Maharashtra"; ?>" class="inp required" id="txtplaceofsupply" placeholder="Debit Note No." data-id="txtplaceofsupply" />
                <div class="error-text" data-id="txtplaceofsupply">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group" style="margin-left: 28px;">
                <div class="inp-label">Debit Note Reference</div>
                <input type="text" value="<?php echo  $debitnoteCopy ? $debitnoteDetails["debit_note_ref"] : ""; ?>" class="inp" id="txtdebitnotereference" placeholder="Debit Note Reference" data-id="txtdebitnotereference" />
                <div class="error-text" data-id="txtdebitnotereference">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <!-- <div class="inp-group" style="margin-left: 28px;">
                <div class="inp-label">PAN No.</div>
                <input type="text" value="<?php //echo  $debitnoteCopy ? $debitnoteDetails["debit_note_pan"] : ""; 
                                            ?>" class="inp" id="txtdebitnotepan" placeholder="Debit Note PAN No." data-id="txtdebitnotepan" />
                <div class="error-text" data-id="txtdebitnotepan">Cannot leave this field blank</div>
            </div> -->
            <!-- inp group end -->

        </div>
        <!-- input row end -->


        <div class="table-row">
            <table>
                <thead>
                    <tr>
                        <th>Sr no.</th>
                        <th>Main Text/Charges</th>
                        <th>Amount</th>
                        <th>HSN</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody id="debitnoteTable">
                </tbody>
                <tfoot>
                    <tr style="color: #aaa;">
                        <td></td>
                        <td>
                            <input type="txt" id="txtmaincharges" placeholder="Main Text/Charges" class="inp" />
                        </td>
                        <td>
                            <input type="txt" id="txtamount" placeholder="Amount" class="inp" />
                        </td>
                        <td>
                            <input type="txt" id="txthsn" placeholder="HSN" class="inp" />
                        </td>
                        <!-- <td></td> -->
                        <td colspan="2">
                            <div class="add-row">Add</div>
                        </td>

                    </tr>
                </tfoot>
            </table>
        </div>

        <table>
            <tr>
                <td style="width: 65%;">CGST</td>
                <td class='calculation--val'>
                    <p id="lblcgst">Rs. 0</p>
                </td>
            </tr>
            <tr>
                <td style="width: 65%;">SGST</td>
                <td class='calculation--val'>
                    <p id="lblsgst">Rs. 0</p>
                </td>
            </tr>
            <tr>
                <td style="width: 65%;">IGST</td>
                <td class='calculation--val'>
                    <p id="lbligst">Rs. 0</p>
                </td>
            </tr>
            <tr>
                <td style="width: 65%;">Round Off</td>
                <td class='calculation--val'>
                    <p id="lblroundoff">Rs. 0</p>
                </td>
            </tr>
            <tr>
                <td style="width: 65%;">Total</td>
                <td class='calculation--val'>
                    <p id="lbltotal">Rs. 0</p>
                </td>
            </tr>

        </table>



        <div class="btn-row">
            <div class="primary-btn btn f-center submit--btn">Submit</div>
        </div>
    </form>



    <script>
        let debitnoteObject = {
            "firm": document.getElementById("ddlfirm").value,
            "bank": document.getElementById("ddlbank").value,
            "supplier": document.getElementById("ddlsupplier").value,
            "date": document.getElementById("txtdebitnotedate").value,
            "reference": document.getElementById("txtdebitnotereference").value,
            "otherreference": document.getElementById("txtdebitnoteotherreference").value,
            // "pan": document.getElementById("txtdebitnotepan").value,
            "no": document.getElementById("txtdebitnoteno").value,
            "gst": document.getElementById("txtgst").value,
            "sgst": document.getElementById("txtsgst").value,
            "cgst": document.getElementById("txtcgst").value,
            "igst": document.getElementById("txtigst").value,
            "placeofsupply": document.getElementById("txtplaceofsupply").value,
            "total": 0,
            "rows": []
        };
    </script>

    <script src="../scripts/helperFunctions.js"></script>
    <script src="scripts/DebitNoteTable.js"></script>
    <script src="scripts/handleDebitNoteTable.js"></script>


    <script>
        let inputs = document.querySelectorAll("form input.required");
        let dropdowns = document.querySelectorAll("form select.required");
        let errorTexts = document.querySelectorAll("form .error-text");

        // insert data if copy
        <?php

        if ($debitnoteCopy) {
            $query = "select * from debit_note_products where debit_note_id = " . $curentId;
            $result = $con->query($query);
            $countOfRows = $result->num_rows;

            if ($countOfRows != 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "debitnotetable.insertRow('" . addslashes($row["debit_note_products_name"]) . "', " . $row["debit_note_products_value"] . ", " . $row["debit_note_products_hsn"] . ");";
                }
                echo "refreshView();";
            }
        }

        ?>
    </script>
    <!-- submitting data -->
    <script>
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
            if (debitnotetable.debitnoteRows.length != 0) {

                // if form is valid
                if (isValid()) {

                    debitnoteObject.firm = document.getElementById("ddlfirm").value;
                    debitnoteObject.bank = document.getElementById("ddlbank").value;
                    debitnoteObject.supplier = document.getElementById("ddlsupplier").value;
                    debitnoteObject.date = document.getElementById("txtdebitnotedate").value;
                    debitnoteObject.reference = document.getElementById("txtdebitnotereference").value;
                    debitnoteObject.otherreference = document.getElementById("txtdebitnoteotherreference").value;
                    debitnoteObject.no = document.getElementById("txtdebitnoteno").value;
                    debitnoteObject.gst = document.getElementById("txtgst").value;
                    debitnoteObject.sgst = document.getElementById("txtsgst").value;
                    debitnoteObject.cgst = document.getElementById("txtcgst").value;
                    debitnoteObject.igst = document.getElementById("txtigst").value;
                    debitnoteObject.placeofsupply = document.getElementById("txtplaceofsupply").value;
                    // debitnoteObject.pan = document.getElementById("txtdebitnotepan").value;

                    // clear all the escape character
                    debitnotetable.debitnoteRows.forEach(debitNoteRow => {
                        debitNoteRow.mainText = escape(debitNoteRow.mainText);
                        debitNoteRow.amount = escape(debitNoteRow.amount);
                        debitNoteRow.hsn = escape(debitNoteRow.hsn);
                    });

                    debitnoteObject.rows = debitnotetable.debitnoteRows;


                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            var result = JSON.parse(this.responseText);

                            if (result.status != "error") {
                                var link = document.createElement('a');
                                link.href = "temp/Debit_Note_" + result.id + "_" + result.user + ".pdf";
                                link.download = "Debit_Note_" + result.id + "_" + result.user + ".pdf";
                                link.dispatchEvent(new MouseEvent('click'));
                                removeLoadingState(submitBtn);
                                showAlert(result.message, result.status);
                                refreshPage();
                            }
                        }
                    };

                    addLoadingState(submitBtn);

                    xmlhttp.open("POST", `services/uploadDebitNote.php`, true);
                    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xmlhttp.send("data=" + JSON.stringify(debitnoteObject));
                }

            } else {
                alert("Please Enter Debite Note Products");
            }
        });
    </script>
    <!-- handle new Firm -->
    <!-- handle gst distribution start -->
    <script>
        let placeOfSupply = document.querySelector('#txtplaceofsupply');
        let gstPercentage = document.querySelector('#txtgst');
        let sgst = document.querySelector('#txtsgst');
        let cgst = document.querySelector('#txtcgst');
        let igst = document.querySelector('#txtigst');
        distibuteGst();

        gstPercentage.addEventListener("change", e => {
            if (gstPercentage.value != "") {
                distibuteGst();
            }
        });
        placeOfSupply.addEventListener("change", e => {
            if (placeOfSupply.value != "") {
                distibuteGst();
            }
        });

        function distibuteGst() {
            if (placeOfSupply.value.toLowerCase() == "maharashtra") {
                cgst.value = gstPercentage.value / 2;
                sgst.value = gstPercentage.value / 2;
                igst.value = 0;

                debitnotetable.setCgst(gstPercentage.value / 2);
                debitnotetable.setSgst(gstPercentage.value / 2);
                debitnotetable.setIgst(0);

            } else {

                debitnotetable.setCgst(0);
                debitnotetable.setSgst(0);
                debitnotetable.setIgst(gstPercentage.value);

                cgst.value = 0;
                sgst.value = 0;
                igst.value = gstPercentage.value;
            }
            refreshView();
        }
    </script>
    <!-- handle gst distribution end -->

    <!-- ddl and gst change handle -->
    <script>
        let selectedFirm = "<?php echo $debitnoteCopy ? $debitnoteDetails["firm_id"] : ""; ?>";
        let selectedBank = "<?php echo $debitnoteCopy ? $debitnoteDetails["firm_bank_id"] : ""; ?>";
        let selectedSupplier = "<?php echo $debitnoteCopy ? $debitnoteDetails["supplier_id"] : ""; ?>";

        let debitnoteDate = document.querySelector('#txtdebitnotedate');
        let debitnoteNo = document.querySelector('#txtdebitnoteno');
        let firmDdl = document.querySelector('#ddlfirm');
        let supplierDdl = document.querySelector('#ddlsupplier');
        let bankDdl = document.querySelector('#ddlbank');


        if (selectedFirm !== "") {
            getBankData(selectedFirm);
            getSupplierData(selectedFirm);
            getSupplierHSN(selectedSupplier);
            getDebitNoteNo(selectedFirm, debitnoteDate.value);
        }

        window.onload = function exampleFunction() {
            getNewData();
        }



        function getDebitNoteNo(firm, date) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    if (result == "1") {
                        debitnoteNo.classList.remove("disabled");
                    } else {
                        <?php echo $user_type != "super_admin" ? "debitnoteNo.classList.add('disabled');" : ""; ?>
                    }
                    debitnoteNo.value = result;
                }
            };
            xmlhttp.open("POST", `services/getDebitNoteNo.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(`firm=${firm}&date=${date}`);
        }

        function getSupplierHSN(supplier) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    hsn.value = result;
                }
            };
            xmlhttp.open("POST", `services/getSupplierHsn.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(`supplier=${supplier}`);
        }


        function getNewData() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    loadDataIntoDdlGeneral(result, firmDdl, "Select Firm", "firm", selectedFirm);
                }
            };
            xmlhttp.open("POST", `services/getNewData.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send();
        }


        function getBankData(firm) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    loadDataIntoDdlGeneral(result, bankDdl, "Select Bank", "bank", selectedBank);
                    bankDdl.classList.remove("disabled");
                }
            };
            xmlhttp.open("POST", `services/getBankList.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send("firm=" + firm);
        }

        function getSupplierData(firm) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    loadDataIntoDdlGeneral(result, supplierDdl, "Select Supplier", "supplier", selectedSupplier);
                    supplierDdl.classList.remove("disabled");
                }
            };
            xmlhttp.open("POST", `services/getSupplierList.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send("firm=" + firm);
        }




        function loadDataIntoDdlGeneral(data, dropdownList, selectEmptyText, type, selectedValue) {
            dropdownList.innerHTML = "<option value=''>" + selectEmptyText + "</option>";

            if (selectedValue !== "") {
                data.forEach(entry => {
                    if (entry[0] == selectedValue) {
                        dropdownList.innerHTML += `<option selected='true' value="${entry[0]}">${entry[1]}</option>`;
                    } else {
                        dropdownList.innerHTML += `<option value="${entry[0]}">${entry[1]}</option>`;
                    }
                });
            } else {
                data.forEach(entry => {
                    dropdownList.innerHTML += `<option value="${entry[0]}">${entry[1]}</option>`;
                });
            }
        }


        // handle firm change
        firmDdl.addEventListener("change", e => {
            if (firmDdl.value != "") {
                getDebitNoteNo(firmDdl.value, debitnoteDate.value);
                getSupplierData(firmDdl.value);
                getBankData(firmDdl.value);
            } else {
                supplierDdl.value = "";
                supplierDdl.classList.add("disabled");
                bankDdl.value = "";
                bankDdl.classList.add("disabled");
            }
        });
        // handle debitnote Date change
        debitnoteDate.addEventListener("change", e => {
            if (debitnoteDate.value != "") {
                getDebitNoteNo(firmDdl.value, debitnoteDate.value);
            }
        });
        // handle Supplier change
        supplierDdl.addEventListener("change", e => {
            if (supplierDdl.value != "") {
                getSupplierHSN(supplierDdl.value);
            } else {
                hsn.value = "";
            }
        });
    </script>
</body>

</html>