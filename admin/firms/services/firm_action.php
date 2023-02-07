<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

session_start();
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];

$currentAction = "firm";

try {
    if ($_POST['action'] == 'edit') {
    }
    if ($_POST['action'] == 'delete') {
        $query = "delete from firm_bank where firm_id = " . $_POST['firm_id'];
        if (mysqli_query($con, $query)) {
            $query = "delete from firm where firm_id = " . $_POST['firm_id'];
            if (mysqli_query($con, $query)) {
                addLog($currentAction, "deleted", "deleted Firm Id: " . $_POST['firm_id'], $con);
            } else {
                $_POST["error"] = "Error in deleting..";
            }
        } else {
            $_POST["error"] = "Error in deleting..";
        }
    }
} catch (exception $e) {
    $_POST["error"] = "Error #1001";
}
echo json_encode($_POST);
