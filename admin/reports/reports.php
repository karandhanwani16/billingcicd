<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head runat="server">
    <link href="../style/spinner.css" rel="stylesheet" />
    <link href="../style/page.css" rel="stylesheet" />
</head>

<body>
    <div class="tab-main-cont">
        <div class="tab-cont">
            <div class="tab fc active-tab" data-link="invoiceReports.php">Invoice Report</div>
            <div class="tab fc" data-link="debitnoteReports.php">Debit Note Report</div>
            <div class="tab fc" data-link="creditnoteReports.php">Credit Note Report</div>
            <div class="tab fc" data-link="detailedReports.php">Detailed Report</div>
        </div>
        <div class="viewport">
            <iframe class="viewport-screen" src="invoiceReports.php"></iframe>
        </div>
    </div>



    <div class="loading-screen fc">
        <div class="loader"></div>
        <p>Loading...</p>
    </div>

    <script src="../scripts/loading.js"></script>
    <script src="../scripts/tabs.js"></script>


</body>

</html>