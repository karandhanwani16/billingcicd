<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

$data = $_POST["data"];
$data =  json_decode($data, true);


session_start();
// checkUrlValidation("admin", "../login.php");
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];


// $images = $data["images"];
$finalObject =  new \stdClass();

//Step 1 : getting all the variables
$name = $data["name"];
$firm = $data["firm"];
$address = $data["address"];
$gst = $data["gst"];
$state = $data["state"];
$statecode = $data["statecode"];
$hsn = $data["hsn"];

//upload all the images

try {
    $maxSupplierId = getCurrentId("supplier_id", "supplier", $con);
    if (!isExistSupplierInFirm($name, $firm, $con)) {
        $sql = "insert into supplier values(" . $maxSupplierId . "," . $firm . ",'" . $name . "','" . $address . "','" . $gst . "','" . $state . "','" . $statecode . "','" . $hsn . "')";
        if (mysqli_query($con, $sql)) {
            addLog("supplier", "created", "", $con);
            $finalObject->status = "success";
            $finalObject->message = "Supplier upload successfully!!!";
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Error #1001";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Supplier already exists!!!";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}

// $image_data = getImageFromBase64($data->image);


$response = json_encode($finalObject);
echo $response;


function isExistSupplierInFirm($name, $firm_id, $con)
{
    $sql = "select * from supplier where supplier_name='" . $name . "' and firm_id = " . $firm_id;
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) == 0) {
        return FALSE;
    } else {
        return TRUE;
    }
    mysqli_close($con);
}
