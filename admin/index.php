<?php
include("services/config.php");
include("services/helperFunctions.php");
session_start();
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];

$currentEmail = getCurrentEmail($user_id, $con);

?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" class="light--theme">

<head runat="server">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yash Enterprises Billing System | Admin</title>
    <link rel="stylesheet" href="style/assets.css">
    <link rel="stylesheet" href="style/main.css">
    <style>
        .chip {
            margin-left: 8px;
            background: none;
            border: 2px solid #c48fd9;
            padding: 2px 16px;
            font-size: 1rem;
            border-radius: 15px;
        }
    </style>
</head>

<body>

    <div class="main-container">
        <div class="sidebar">
            <ul class="menu-cont">

                <?php
                $currentPage = "./reports/reports.php";
                if ($user_type == "salescord") {
                    $currentPage = "./invoice/invoice.php";
                }
                ?>

                <li class="menu-item <?php echo $user_type == "salescord" ? "hidden" : "mainactive"; ?>">
                    <a class="menu-item-main f-link" data-link="./reports/reports.php">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Reports</div>
                    </a>
                </li>
                <li class="menu-item <?php echo $user_type == "salescord" ? "mainactive" : ""; ?>">
                    <a class="menu-item-main f-link" data-link="./invoice/invoice.php">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Invoice</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a class="menu-item-main f-link" data-link="./debit_note/debit_note.php">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Debit Note</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-item-main f-link" data-link="./credit_note/credit_note.php">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Credit Note</div>
                    </a>
                </li>
                <li class="menu-item <?php echo $user_type == "salescord" ? "hidden" : ""; ?>">
                    <a class="menu-item-main f-link" data-link="./editrequest/editrequest.php">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Edit Request</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a class="menu-item-main f-link" data-link="supplier/supplier.php">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Buyer Details</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a class="menu-item-main f-link" data-link="firms/firms.php">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Firms</div>
                    </a>
                </li>

                <li class="menu-item ddl <?php echo $user_type == "salescord" ? "hidden" : ""; ?>">
                    <div class="menu-item-main">
                        <img src="assets/icons/catalogue.svg" alt="">
                        <div class="list-item-value">Super Admin</div>
                    </div>
                    <ul class="sub-menu hidden">
                        <li class="submenu-list-item f-link" data-link="superadmin/addusers/addusers.php"><a>Add user</a></li>
                        <li class="submenu-list-item f-link" data-link="superadmin/logs/logs.php"><a>Logs</a></li>
                        <li class="submenu-list-item f-link" data-link="superadmin/backup/backup.php"><a>Backup</a></li>
                    </ul>
                </li>


                <li class="menu-item">
                    <a class="menu-item-main f-link" data-link="../changepassword.php?admin=1&email=<?php echo $currentEmail; ?>">
                        <img src="assets/icons/dashboard.svg" alt="">
                        <div class="list-item-value">Change Password</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a class="menu-item-main" href="logout.php">
                        <img src="assets/icons/logout.svg" alt="">
                        <div class="list-item-value">Logout</div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-view">
            <div class="top-nav">
                <div class="ham-menu f-center">
                    <img src="assets/icons/ham-menu.svg" alt="">
                </div>
            </div>
            <div class="main-content-page">
                <iframe style="width:100%;height:100%;border:none;" class="target-container" src="<?php echo $currentPage; ?>"></iframe>
            </div>
        </div>
    </div>

    <!-- scripts start -->
    <script src="scripts/sidebar.js"></script>
    <!-- scripts end -->

</body>

</html>