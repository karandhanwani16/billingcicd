<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

$data = $_POST["data"];
$data =  json_decode($data, true);

session_start();
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];

// initializing the final object
$finalObject =  new \stdClass();

//Step 1 : getting all the variables
$name = $data["name"];
$gst = $data["gst"];
$address = $data["address"];
$state = $data["state"];
$statecode = $data["statecode"];
$banks = $data["banks"];


try {
    $maxFirmId = getCurrentId("firm_id", "firm", $con);
    if (!isExist($name, "firm", "firm_name", $con)) {
        $sql = "insert into firm values(" . $maxFirmId . ",'" . $name . "','" . $gst . "','" . $address . "','" . $state . "','" . $statecode . "')";
        if (mysqli_query($con, $sql)) {
            // upload the banks
            if (uploadBanks($maxFirmId, $banks, $con)) {
                addLog("firm", "created", "", $con);
                $finalObject->status = "success";
                $finalObject->message = "Firm upload successfully!!!";
            } else {
                deleteOnError("firm", "firm_id", $maxFirmId, $con);
                $finalObject->status = "error";
                $finalObject->message = "Error #1002";
            }
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Error #1001";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Firm already exists!!!";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}

// $image_data = getImageFromBase64($data->image);


$response = json_encode($finalObject);
echo $response;


// common functions
function deleteOnError($table_name, $primary_id_column_name, $primaryId, $con)
{
    $query = "delete from " . $table_name . " where " . $primary_id_column_name . " = " . $primaryId;
    mysqli_query($con, $query);
}


function uploadBanks($currentFirmId, $banks, $con)
{
    $uploaded = true;
    for ($i = 0; $i < count($banks); $i++) {
        $maxFirmBankId = getCurrentId("firm_bank_id", "firm_bank", $con);
        $sql = "insert into firm_bank values(" . $maxFirmBankId . "," . $currentFirmId . ",'" . $banks[$i]["bank_name"] . "','" . $banks[$i]["account_no"] . "','" . $banks[$i]["branch_name"] . "','" . $banks[$i]["ifsc"] . "')";
        if (!mysqli_query($con, $sql)) {
            $uploaded = false;
        }
    }
    return $uploaded;
}
