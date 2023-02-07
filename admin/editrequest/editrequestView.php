<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/assets.css">
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

        .accept-btn {
            background-color: var(--success-color);
            color: #fff;
            margin-right: 18px;
        }

        .decline-btn,
        .download-btn {
            background-color: var(--danger-color);
            color: #fff;
        }

        .download-btn:hover {
            cursor: pointer;
            opacity: 0.9;
            color: #fff;
        }

        .data-value {
            width: 100%;
            text-align: left !important;
        }
    </style>
</head>

<body>
    <div class="alert--cont">
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Invoice Edit Request</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="sample_data" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Actions</th>
                            <th>Download</th>
                            <th>Invoice ID</th>
                            <th>User</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../scripts/helperFunctions.js"></script>

    <script>
        function addAcceptDeclineListerners() {
            let acceptButtons = document.querySelectorAll(".accept-btn");
            let declineButtons = document.querySelectorAll(".decline-btn");
            acceptButtons.forEach(acceptButton => {
                acceptButton.addEventListener("click", e => {
                    let currentRequestId = acceptButton.attributes["data-id"].value;
                    editRequestAction(acceptButton, currentRequestId, "accept");
                });
            });
            declineButtons.forEach(declineButton => {
                declineButton.addEventListener("click", e => {
                    let currentRequestId = declineButton.attributes["data-id"].value;
                    editRequestAction(declineButton, currentRequestId, "decline");
                });
            });
        }

        function editRequestAction(element, request_id, status) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    removeLoadingStateWithText(element, status == "accept" ? "Accept Request" : "Decline Request");
                    $('#sample_data').DataTable().ajax.reload();

                }
            };
            addLoadingStateWithText(element, status == "accept" ? "accepting.." : "declining...");
            xmlhttp.open("POST", `services/editRequestAction.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(`id=${request_id}&status=${status}`);
        }
    </script>
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
                    addAcceptDeclineListerners();
                    let downloadBtns = document.querySelectorAll(".download-btn");
                    downloadBtns.forEach(downloadBtn => {
                        downloadBtn.addEventListener("click", e => {
                            let id = downloadBtn.attributes["data-id"].value;
                            downloadPdf(downloadBtn, id);
                        });
                    });
                }
            });

            function downloadPdf(btn, id) {
                let invoiceId = id;
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(this.responseText);
                        removeLoadingStateWithText(btn, "Download PDF");
                        var link = document.createElement('a');
                        link.href = "../invoice/temp/Invoice_" + result.id + "_" + result.user + ".pdf";
                        link.download = "Invoice_" + result.id + "_" + result.user + ".pdf";
                        link.dispatchEvent(new MouseEvent('click'));
                    }
                };
                addLoadingStateWithText(btn, "Downloading...");
                xmlhttp.open("POST", `../invoice/services/getInvoicePdf.php`, true);
                xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xmlhttp.send("id=" + invoiceId);
            }

        });
    </script>
    <!-- table data end -->
</body>

</html>