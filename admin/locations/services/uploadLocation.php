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
$active = $data["active"];

//upload all the images

try {
    if ($user_type != "salescord") {
        $maxLocationId = getCurrentId("location_id", "location", $con);
        if (!isExist($name, "location", "location_name", $con)) {
            $sql = "insert into location values(" . $maxLocationId . ",'" . $name . "','" . $active . "')";
            if (mysqli_query($con, $sql)) {
                addLog("location", "created", "", $con);
                $finalObject->status = "success";
                $finalObject->message = "Location upload successfully!!!";
            } else {
                $finalObject->status = "error";
                $finalObject->message = "Error #1001";
            }
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Location already exists!!!";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "You are not authorized to upload";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}

// $image_data = getImageFromBase64($data->image);


$response = json_encode($finalObject);
echo $response;
