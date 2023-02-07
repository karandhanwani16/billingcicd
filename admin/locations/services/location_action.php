<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

session_start();
// checkUrlValidation("admin", "../login.php");
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];

$currentAction = "location";

try {
    if ($_POST['action'] == 'edit') {
        if ($user_type != "salescord") {
            $name = addslashes($_POST["location_name"]);
            $active = addslashes($_POST["location_active"]);
            if (!isExistUpdate($name, $currentAction, "location_name", $_POST['location_id'], "location_id", $con)) {
                $query = "update location set location_name='" . $name . "',location_active='" . $active . "' where location_id =" . $_POST['location_id'];
                if (mysqli_query($con, $query)) {
                    addLog($currentAction, "updated", "updated Location Id: " . $_POST['location_id'], $con);
                }
            } else {
                $_POST["error"] = "Location Name Already Exist!!!";
            }
        } else {
            $_POST["error"] = "You are not Authorized to Edit";
        }
    }
    if ($_POST['action'] == 'delete') {
        if ($user_type != "salescord") {
            $query = "delete from location where location_id = " . $_POST['location_id'];
            if (mysqli_query($con, $query)) {
                addLog($currentAction, "deleted", "deleted Location Id: " . $_POST['location_id'], $con);
            }
        } else {
            $_POST["error"] = "You are not Authorized to Delete";
        }
    }
} catch (exception $e) {
    $_POST["error"] = "Error #1001";
}
echo json_encode($_POST);
