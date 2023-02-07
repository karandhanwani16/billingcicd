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
    <title>Get Backup</title>
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

    <div class="title">Get backup</div>
    <div class="spacer"></div>

    <form action="#">
        <!-- input row end -->
        <div class="btn-row">
            <div class="primary-btn btn f-center submit--btn">Backup</div>
        </div>
    </form>

    <script src="../../scripts/helperFunctions.js"></script>
    <!-- submitting data -->
    <script>
        let submitBtn = document.querySelector(".submit--btn");
        submitBtn.addEventListener("click", e => {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);

                    // if (result.status == "success") {
                    //     let pdfUrl = result.filename;
                    //     var link = document.createElement('a');
                    //     link.href = pdfUrl;
                    //     link.addEventListener("click", e => {
                    //         e.preventDefault();
                    //     });
                    //     link.dispatchEvent(new MouseEvent('click'));
                    // }

                    removeLoadingStateWithText(submitBtn, "Backup");
                    showAlert(result.message, result.status);
                }
            };
            addLoadingStateWithText(submitBtn, "Backing up...");
            xmlhttp.open("POST", `services/getBackupService.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send();
        });
    </script>
</body>

</html>