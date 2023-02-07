<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

$currentFirmId = $_POST["id"];

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
    if (!isUpdateExist($name, "firm", "firm_name", $currentFirmId, "firm_id", $con)) {
        $sql = "update firm set firm_name = '" . $name . "',firm_gst = '" . $gst . "',firm_address = '" . $address . "',firm_state = '" . $state . "',firm_state_code = '" . $statecode . "' where firm_id = " . $currentFirmId;
        if (mysqli_query($con, $sql)) {
            // delete the banks
            if (deleteBanks($currentFirmId, $con)) {
                // upload the banks
                if (uploadBanks($currentFirmId, $banks, $con)) {
                    addLog("firm", "updated", "", $con);
                    $finalObject->status = "success";
                    $finalObject->message = "Firm updated successfully!!!";
                } else {
                    $finalObject->status = "error";
                    $finalObject->message = "Error #1003";
                }
            } else {
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



function deleteBanks($currentFirmId, $con)
{
    $deleted = true;
    $sql = "delete from firm_bank where firm_id = " . $currentFirmId;
    if (!mysqli_query($con, $sql)) {
        $deleted = false;
    }
    return $deleted;
}
