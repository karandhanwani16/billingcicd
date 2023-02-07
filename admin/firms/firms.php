<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head runat="server">
    <link href="../style/spinner.css" rel="stylesheet" />
    <link href="../style/page.css" rel="stylesheet" />
</head>

<body>
    <div class="tab-main-cont">
        <div class="tab-cont">
            <div class="tab fc active-tab" data-link="firmUpload.php">Upload</div>
            <div class="tab fc" data-link="firmView.php">View</div>
        </div>
        <div class="viewport">
            <iframe class="viewport-screen" src="firmUpload.php"></iframe>
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