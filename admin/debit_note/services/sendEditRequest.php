<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

$id = $_POST["id"];
$message = $_POST["message"];

session_start();
// checkUrlValidation("admin", "../login.php");
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];


// $images = $data["images"];
$finalObject =  new \stdClass();

try {
    $maxRequestId = getCurrentId("debit_note_edit_request_id", "debit_note_edit_request", $con);

    $sql = "insert into debit_note_edit_request values(" . $maxRequestId . "," . $id . "," . $user_id . ",'" . getCurrentTimestamp() . "','false','false','" . $message . "')";
    if (mysqli_query($con, $sql)) {
        addLog("Edit Request", "created", "Request Created by " . $user_id . " for Invoice Id " . $id, $con);
        $finalObject->status = "success";
        $finalObject->message = "Request Send Successfully and your Request ID is " . $maxRequestId . ".";
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Error #1001";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}

$response = json_encode($finalObject);
echo $response;
