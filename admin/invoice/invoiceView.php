<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
    <!-- <link rel="stylesheet" href="../style/forms.css">  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        .disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .btn {
            padding: 10px 24px;
            font-weight: 600;
            border-radius: 12px;
        }

        .select-btn,
        .request-btn {
            color: #eee;
            background-color: #222;
        }

        .select-btn:hover,
        .request-btn:hover {
            color: #eee;
            cursor: pointer;
            text-decoration: none;
            opacity: 0.9;
        }

        .select-btn {
            background-color: #1ca0de;
        }

        .label {
            display: inline-block;
            background-color: rgba(50, 50, 50, 0.05);
            border-radius: 0.5em;
            color: #1a1a1a;
            border: 4px solid #666;
            font-size: 1.25rem;
            padding: 8px 14px;
            white-space: nowrap;
        }

        /* popup start */
        .popup-bg {
            width: 100%;
            height: 100%;
            background: rgba(50, 50, 50, 0.5);
            position: absolute;
            top: 0;
            left: 0;
        }

        .popup {
            border-radius: 12px;
            background: #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 18px;
        }

        .roww {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .popup h3 {
            margin: 0;
            margin-right: 56px;

        }

        .close-btn {
            border-radius: 100%;
            padding: 8px;
            background: #eee;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .close-btn img {
            height: 12px;
            width: 12px;
        }

        .close-btn:hover {
            cursor: pointer;
        }

        .invoice-details {
            margin-top: 12px;
        }

        .data-label {
            width: 128px;
            margin-right: 12px;
            font-weight: 700;
        }

        .message {
            margin: 24px 0;
        }

        .message input {
            background: #f6f5f4;
            border: 1px solid #bbb;
            font-size: 1.5rem;
            padding: 8px 16px;
            border-radius: 8px;
            width: 100%;
            font-weight: 700;
        }

        .submit-btn {
            padding: 8px 16px;
            background: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 700;
            border-radius: 8px;
            color: #fff;
            text-align: center;
        }

        .submit-btn:hover {
            cursor: pointer;
            opacity: 0.9;
        }

        .delete-btn {
            background-color: var(--danger-color);
            color: #fff;
        }

        .download-btn {
            background-color: #1ca0de;
            margin-bottom: 12px;
            color: #fff;
        }

        .download-btn:hover,
        .delete-btn:hover {
            cursor: pointer;
            opacity: 0.9;
            color: #fff;
        }

        .data-value {
            width: 100%;
            text-align: left !important;
        }

        ul {
            list-style-type: none;
        }
    </style>


</head>

<body>
    <div class="alert--cont">
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Invoice</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="sample_data" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Copy</th>
                            <th>Actions</th>
                            <th>Download</th>
                            <th>Firm</th>
                            <th>Supplier</th>
                            <th>Reference</th>
                            <th>Invoice No.</th>
                            <th>Date</th>
                            <th>Bank</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="hidden popup-bg"></div>
    <div class="hidden popup">
        <div class="roww">
            <h3><b>Edit Request</b></h3>
            <div class="close-btn">
                <img src="../assets/icons/close.svg" alt="">
            </div>
        </div>

        <div class="invoice-details">
            <div class="roww">
                <div class="data-label">Invoice ID</div>
                <div class="data-value" id="lblinvoiceid">1</div>
            </div>
            <div class="roww">
                <div class="data-label">Firm name</div>
                <div class="data-value" id="lblfirm">Firm</div>
            </div>
            <div class="roww">
                <div class="data-label">Supplier</div>
                <div class="data-value" id="lblsupplier">Karan</div>
            </div>
            <div class="roww">
                <div class="data-label">Invoice No.</div>
                <div class="data-value" id="lblinvoiceno">1250</div>
            </div>
            <div class="roww">
                <div class="data-label">Invoice Date.</div>
                <div class="data-value" id="lblinvoicedate">2022-10-20</div>
            </div>
        </div>

        <div class="message">
            <input type="text" placeholder="Edit Request Message" id="txtmessage" />
        </div>


        <div class="submit-btn disabled" data-id="">Send Edit Request</div>

    </div>


    <script src="../scripts/helperFunctions.js"></script>
    <!-- pop up start -->
    <script>
        // get all the variables
        let popup = document.querySelector(".popup");
        let popupBackground = document.querySelector(".popup-bg");
        let closeBtn = document.querySelector(".close-btn");
        let submitButton = document.querySelector(".submit-btn");


        let invoiceIdLabel = document.querySelector("#lblinvoiceid");
        let firmLabel = document.querySelector("#lblfirm");
        let supplierLabel = document.querySelector("#lblsupplier");
        let invoiceNoLabel = document.querySelector("#lblinvoiceno");
        let invoiceDateLabel = document.querySelector("#lblinvoicedate");

        // utils functions
        function openPopup() {
            popup.classList.remove("hidden");
            popupBackground.classList.remove("hidden");
        }

        function closePopup() {
            popup.classList.add("hidden");
            popupBackground.classList.add("hidden");
        }

        // event Listeners
        function addPopupListener() {
            let editReuestButtons = document.querySelectorAll(".request-btn");
            editReuestButtons.forEach(editReuestButton => {
                editReuestButton.addEventListener("click", e => {
                    let currentInvoiceId = editReuestButton.attributes["data-id"].value;
                    openPopup();
                    getInvoiceData(currentInvoiceId);
                });
            });
        }
        popupBackground.addEventListener("click", e => {
            closePopup();
        });
        closeBtn.addEventListener("click", e => {
            closePopup();
        });


        // main functions
        // load data function
        function getInvoiceData(invoice_id) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    if (!(Object.keys(result).length === 0)) {
                        loadDataIntoPopUpLabels(result.invoice_id, result.firm_name, result.supplier_name, result.invoice_no, result.invoice_date);
                        submitButton.classList.remove("disabled");
                        submitButton.attributes["data-id"].value = invoice_id;
                    } else {
                        submitButton.classList.add("disabled");
                    }
                }
            };
            loadDataIntoPopUpLabels("loading", "loading", "loading", "loading", "loading");
            xmlhttp.open("POST", `services/getInvoiceLabelData.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(`id=${invoice_id}`);
        }
        // load labels
        function loadDataIntoPopUpLabels(invoice_id, firm_name, supplier_name, invoice_no, invoice_date) {
            invoiceIdLabel.innerHTML = invoice_id;
            firmLabel.innerHTML = firm_name;
            supplierLabel.innerHTML = supplier_name;
            invoiceNoLabel.innerHTML = invoice_no;
            invoiceDateLabel.innerHTML = invoice_date;
        }
    </script>
    <!-- pop up end -->

    <!-- table data start -->
    <script type="text/javascript" language="javascript">
        $(document).ready(function() {

            var dataTable = $('#sample_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "services/getInvoiceData.php",
                    type: "POST"
                },
                "drawCallback": function(oSettings) {
                    addPopupListener();
                    let downloadBtns = document.querySelectorAll(".download-btn");
                    downloadBtns.forEach(downloadBtn => {
                        downloadBtn.addEventListener("click", e => {
                            let id = downloadBtn.attributes["data-id"].value;
                            downloadPdf(downloadBtn, id);
                        });
                    });
                    let deleteBtns = document.querySelectorAll(".delete-btn");
                    deleteBtns.forEach(deleteBtn => {
                        deleteBtn.addEventListener("click", e => {
                            let id = deleteBtn.attributes["data-id"].value;
                            let deleteConfirm = confirm("Are you Sure You want to delete Invoice Id " + id + " ?");
                            if (deleteConfirm) {
                                deleteInvoice(deleteBtn, id);
                            }
                        });
                    });
                }
            });

            // $('#sample_data').on('draw.dt', function() {
            //     $('#sample_data').Tabledit({
            //         url: 'services/invoice_action.php',
            //         dataType: 'json',
            //         columns: {
            //             identifier: [0, 'invoice_id']
            //         },
            //         restoreButton: false,
            //         onSuccess: function(data, textStatus, jqXHR) {

            //             if (data.error) {
            //                 showAlert(data.error, "error");
            //             }
            //             if (data.action == 'delete') {
            //                 $('#' + data.id).remove();
            //                 $('#sample_data').DataTable().ajax.reload();
            //             }
            //         }
            //     });
            // });



            function downloadPdf(btn, id) {
                let invoiceId = id;
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingStateWithText(btn, "Download PDF");
                        var link = document.createElement('a');
                        link.href = "temp/Invoice_" + result.id + "_" + result.user + ".pdf";
                        link.download = "Invoice_" + result.id + "_" + result.user + ".pdf";
                        link.dispatchEvent(new MouseEvent('click'));
                    }
                };
                addLoadingStateWithText(btn, "Downloading...");
                xmlhttp.open("POST", `services/getInvoicePdf.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("id=" + invoiceId);
            }

            function deleteInvoice(btn, id) {
                let invoiceId = id;
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingStateWithText(btn, "Delete");
                        showAlert(result.message, result.status);
                        if (result.status == "success") {
                            $('#' + result.id).remove();
                            $('#sample_data').DataTable().ajax.reload();
                        }
                    }
                };
                addLoadingStateWithText(btn, "Deleting...");
                xmlhttp.open("POST", `services/deleteInvoice.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("id=" + invoiceId);
            }

        });
    </script>
    <!-- table data end -->

    <!-- edit request start -->
    <script>
        submitButton.addEventListener("click", e => {
            let currentInvoiceId = submitButton.attributes["data-id"].value;
            let editMessage = document.querySelector("#txtmessage");

            if (editMessage.value != "") {

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingStateWithText(submitButton, "Send Edit Request");
                        closePopup();
                        showAlert(result.message, result.status);
                        $('#sample_data').DataTable().ajax.reload();
                    }
                };
                addLoadingStateWithText(submitButton, "Sending Edit Request...");
                xmlhttp.open("POST", `services/sendEditRequest.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send(`id=${currentInvoiceId}&message=${editMessage.value}`);
            } else {
                alert("Please Enter Message for this Edit Request");
            }
        });
    </script>
    <!-- edit request end -->

</body>

</html>