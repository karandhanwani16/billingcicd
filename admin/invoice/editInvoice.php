<?php

include("../services/urlValidation.php");
include("../services/config.php");
include("../services/helperFunctions.php");

$invoiceEdit = isset($_GET["id"]);
$currentId = 0;

session_start();
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];


if ($invoiceEdit) {
    $currentId = $_GET["id"];
    $isEditAllowed = getIsEditAllowed($currentId, $user_id, $con);

    if (!$isEditAllowed) {
        header("Location: invoiceView.php");
    }
    $invoiceDetails = getTableDetails("invoice", "invoice_id", $currentId, $con);
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

function getIsEditAllowed($invoice_id, $user_id, $con)
{
    $isAllowed = false;
    $query = "select invoice_edit_request_id from invoice_edit_request where invoice_edit_request_used='false' and invoice_edit_request_permission_granted='true' and invoice_id = " . $invoice_id . " and user_id = " . $user_id;
    $result = $con->query($query);
    $countOfRows = $result->num_rows;
    if ($countOfRows != 0) {
        $isAllowed = true;
    }
    return $isAllowed;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Edit</title>
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

        ul {
            list-style-type: none;
        }
    </style>
</head>

<body>

    <?php
    if ($invoiceEdit) {
        echo "<a onclick='history.back()' class='back-btn-cont'><img src='../assets/icons/back.svg' alt='back-btn'></a>";
    }
    ?>


    <div class="alert--cont">
    </div>

    <!-- alert cont end -->

    <div class="title">Invoices</div>
    <div class="spacer"></div>

    <form action="#">
        <div class="inp-row adj-row row-5">
            <div class="inp-group">
                <div class="inp-label">Invoice's Firm</div>
                <select id="ddlfirm" class="ddl required" data-id="ddlfirm">
                    <option value="">Select Invoice's Firm</option>
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
                <div class="inp-label">Invoice Date</div>
                <input type="date" value="<?php echo $invoiceDetails["invoice_date"]; ?>" class="inp required" id="txtinvoicedate" data-id="txtinvoicedate" />
                <div class="error-text" data-id="txtinvoicedate">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">Invoice PO No.</div>
                <input type="text" value="<?php echo  $invoiceEdit ? $invoiceDetails["invoice_other_ref"] : ""; ?>" class="inp required" id="txtinvoiceotherreference" placeholder="Invoice PO No." data-id="txtinvoiceotherreference" />
                <div class="error-text" data-id="txtinvoiceotherreference">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->

        </div>
        <!-- input row end -->
        <div class="inp-row adj-row row-5">
            <div class="inp-group">
                <div class="inp-label">Invoice No.</div>
                <input type="text" value="<?php echo $invoiceDetails["invoice_no"]; ?>" class="inp required <?php echo $user_type != "super_admin" ? "disabled" : ""; ?>" tabindex="-1" id="txtinvoiceno" placeholder="Invoice No." data-id="txtinvoiceno" />
                <div class="error-text" data-id="txtinvoiceno">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group">
                <div class="inp-label">GST Percentage</div>
                <input type="text" value="<?php echo  $invoiceEdit ? (strtolower($invoiceDetails["invoice_place_of_supply"]) == "maharashtra" ? $invoiceDetails["invoice_sgst_percentage"] * 2 : $invoiceDetails["invoice_igst_percentage"]) : ""; ?>" class="inp required" id="txtgst" placeholder="GST Percentage" data-id="txtgst" />
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
                <input type="text" value="<?php echo  $invoiceEdit ? $invoiceDetails["invoice_place_of_supply"] : "Maharashtra"; ?>" class="inp required" id="txtplaceofsupply" placeholder="Invoice No." data-id="txtplaceofsupply" />
                <div class="error-text" data-id="txtplaceofsupply">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <div class="inp-group" style="margin-left: 28px;">
                <div class="inp-label">Invoice Reference</div>
                <input type="text" value="<?php echo  $invoiceEdit ? $invoiceDetails["invoice_ref"] : ""; ?>" class="inp" id="txtinvoicereference" placeholder="Invoice Reference" data-id="txtinvoicereference" />
                <div class="error-text" data-id="txtinvoicereference">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
            <!-- <div class="inp-group" style="margin-left: 28px;">
                <div class="inp-label">PAN No.</div>
                <input type="text" value="<?php //echo  $invoiceEdit ? $invoiceDetails["invoice_pan"] : ""; 
                                            ?>" class="inp" id="txtinvoicepan" placeholder="Invoice Reference" data-id="txtinvoicepan" />
                <div class="error-text" data-id="txtinvoicepan">Cannot leave this field blank</div>
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
                        <th>Prefix Comment</th>
                        <th>Total Value</th>
                        <th>Postfix Comment</th>
                        <th>Comission Percentage</th>
                        <th>HSN</th>
                        <th>Value</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody id="invoiceTable">
                </tbody>
                <tfoot>
                    <tr style="color: #aaa;">
                        <td></td>
                        <td>
                            <input type="txt" id="txtmaincharges" placeholder="Main Text/Charges" class="inp" />
                        </td>
                        <td>
                            <input type="txt" id="txtprefix" placeholder="Prefix Comment" class="inp" />
                        </td>
                        <td>
                            <input type="txt" id="txttotalvalue" placeholder="Total value" class="inp" />
                        </td>
                        <td>
                            <input type="txt" id="txtpostfix" placeholder="Postfix Comment" class="inp" />
                        </td>
                        <td>
                            <input type="txt" id="txtcomissionpercentage" placeholder="Comission Percentage" class="inp" />
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
        let invoiceObject = {
            "id": "<?php echo $invoiceEdit ? $currentId : ""; ?>",
            "firm": document.getElementById("ddlfirm").value,
            "bank": document.getElementById("ddlbank").value,
            "supplier": document.getElementById("ddlsupplier").value,
            "date": document.getElementById("txtinvoicedate").value,
            "reference": document.getElementById("txtinvoicereference").value,
            "otherreference": document.getElementById("txtinvoiceotherreference").value,
            // "pan": document.getElementById("txtinvoicepan").value,
            "no": document.getElementById("txtinvoiceno").value,
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
    <script src="scripts/InvoiceTable.js"></script>
    <script src="scripts/handleInvoiceTable.js"></script>


    <script>
        let inputs = document.querySelectorAll("form input.required");
        let dropdowns = document.querySelectorAll("form select.required");
        let errorTexts = document.querySelectorAll("form .error-text");

        // insert data if copy
        <?php

        if ($invoiceEdit) {
            $query = "select * from invoice_products where invoice_id = " . $currentId;
            $result = $con->query($query);
            $countOfRows = $result->num_rows;

            if ($countOfRows != 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "banktable.insertRow('" . addslashes($row["Invoice_products_name"]) . "', '" . addslashes($row["Invoice_products_prefix"]) . "', '" . addslashes($row["Invoice_products_postfix"]) . "', " . $row["Invoice_products_value"] . ", " . $row["Invoice_products_percentage"] . ", " . $row["Invoice_products_hsn"] . ");";
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
            if (banktable.invoiceRows.length != 0) {

                // if form is valid
                if (isValid()) {

                    invoiceObject.firm = document.getElementById("ddlfirm").value;
                    invoiceObject.bank = document.getElementById("ddlbank").value;
                    invoiceObject.supplier = document.getElementById("ddlsupplier").value;
                    invoiceObject.date = document.getElementById("txtinvoicedate").value;
                    invoiceObject.reference = document.getElementById("txtinvoicereference").value;
                    invoiceObject.otherreference = document.getElementById("txtinvoiceotherreference").value;
                    invoiceObject.no = document.getElementById("txtinvoiceno").value;
                    invoiceObject.gst = document.getElementById("txtgst").value;
                    invoiceObject.sgst = document.getElementById("txtsgst").value;
                    invoiceObject.cgst = document.getElementById("txtcgst").value;
                    invoiceObject.igst = document.getElementById("txtigst").value;
                    invoiceObject.placeofsupply = document.getElementById("txtplaceofsupply").value;
                    // invoiceObject.pan = document.getElementById("txtinvoicepan").value;

                    // clear all the escape character
                    banktable.invoiceRows.forEach(invoiceRow => {
                        invoiceRow.mainText = escape(invoiceRow.mainText);
                        invoiceRow.prefixText = escape(invoiceRow.prefixText);
                        invoiceRow.postfixText = escape(invoiceRow.postfixText);
                        invoiceRow.totalValue = escape(invoiceRow.totalValue);
                        invoiceRow.percentage = escape(invoiceRow.percentage);
                        invoiceRow.rowValue = escape(invoiceRow.rowValue);
                        invoiceRow.hsn = escape(invoiceRow.hsn);
                    });

                    invoiceObject.rows = banktable.invoiceRows;;

                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            var result = JSON.parse(this.responseText);

                            if (result.status != "error") {
                                var link = document.createElement('a');
                                link.href = "temp/Invoice_" + result.id + "_" + result.user + ".pdf";
                                link.download = "Invoice_" + result.id + "_" + result.user + ".pdf";
                                link.dispatchEvent(new MouseEvent('click'));
                            }
                            removeLoadingState(submitBtn);
                            showAlert(result.message, result.status);
                            refreshPage();

                        }
                    };

                    addLoadingState(submitBtn);

                    xmlhttp.open("POST", `services/updateInvoice.php`, true);
                    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xmlhttp.send("data=" + JSON.stringify(invoiceObject));
                }

            } else {
                alert("Please Enter Invoice Products");
            }

            // var xmlhttp = new XMLHttpRequest();
            // xmlhttp.onreadystatechange = function() {
            //     if (this.readyState == 4 && this.status == 200) {
            //         var result = JSON.parse(this.responseText);
            //         if (result.status != "error") {
            //             var link = document.createElement('a');
            //             link.href = "temp/Invoice_" + result.id + "_" + result.user + ".pdf";
            //             link.download = "Invoice_" + result.id + "_" + result.user + ".pdf";
            //             link.dispatchEvent(new MouseEvent('click'));
            //             // removeLoadingState(submitBtn);
            //             showAlert(result.message, result.status);
            //             // refreshPage();
            //         }
            //     }
            // };


            // xmlhttp.open("POST", `services/getInvoicePdf.php`, true);
            // xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            // xmlhttp.send("id=9");
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

                banktable.setCgst(gstPercentage.value / 2);
                banktable.setSgst(gstPercentage.value / 2);
                banktable.setIgst(0);

            } else {

                banktable.setCgst(0);
                banktable.setSgst(0);
                banktable.setIgst(gstPercentage.value);

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
        let selectedFirm = "<?php echo $invoiceEdit ? $invoiceDetails["firm_id"] : ""; ?>";
        let selectedBank = "<?php echo $invoiceEdit ? $invoiceDetails["firm_bank_id"] : ""; ?>";
        let selectedSupplier = "<?php echo $invoiceEdit ? $invoiceDetails["supplier_id"] : ""; ?>";

        let invoiceDate = document.querySelector('#txtinvoicedate');
        let invoiceNo = document.querySelector('#txtinvoiceno');
        let firmDdl = document.querySelector('#ddlfirm');
        let supplierDdl = document.querySelector('#ddlsupplier');
        let bankDdl = document.querySelector('#ddlbank');


        if (selectedFirm !== "") {
            getBankData(selectedFirm);
            getSupplierData(selectedFirm);
            getSupplierHSN(selectedSupplier);
        }

        window.onload = function exampleFunction() {
            getNewData();
        }



        function getInvoiceNo(firm, date) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    if (result == "1") {
                        invoiceNo.classList.remove("disabled");
                    } else {
                        <?php echo $user_type != "super_admin" ? "invoiceNo.classList.add('disabled');" : ""; ?>
                    }
                    invoiceNo.value = result;
                }
            };
            xmlhttp.open("POST", `services/getInvoiceNo.php`, true);
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
                getInvoiceNo(firmDdl.value, invoiceDate.value);
                getSupplierData(firmDdl.value);
                getBankData(firmDdl.value);
            } else {
                supplierDdl.value = "";
                supplierDdl.classList.add("disabled");
                bankDdl.value = "";
                bankDdl.classList.add("disabled");
            }
        });
        // handle invoice Date change
        invoiceDate.addEventListener("change", e => {
            if (invoiceDate.value != "") {
                getInvoiceNo(firmDdl.value, invoiceDate.value);
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