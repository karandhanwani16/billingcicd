<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";

$currentAction = "supplier";

session_start();
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];


try {
    if ($_POST['action'] == 'edit') {

        $name = addslashes($_POST["supplier_name"]);
        $address = addslashes($_POST["supplier_address"]);
        $gst = addslashes($_POST["supplier_gst_no"]);
        $state = addslashes($_POST["supplier_state"]);
        $statecode = addslashes($_POST["supplier_state_code"]);
        $hsn = addslashes($_POST["supplier_hsn_code"]);
        // echo isExistSupplierUpdate($name, $_POST['supplier_id'], $_POST['firm_id'], $con);

        if (!isExistSupplierUpdate($name, $_POST['supplier_id'], $_POST['firm_id'], $con)) {
            $query = "update supplier set supplier_name= '" . $name . "',supplier_address= '" . $address . "',supplier_gst_no= '" . $gst . "',supplier_state= '" . $state . "',supplier_state_code= '" . $statecode . "',supplier_hsn_code= '" . $hsn . "' where supplier_id = " . $_POST['supplier_id'];
            if (mysqli_query($con, $query)) {
                addLog($currentAction, "updated", "updated Supplier Id: " . $_POST['supplier_id'], $con);
            }
        } else {
            $_POST["error"] = "Supplier Name Already Exist!!!";
        }
    }
    if ($_POST['action'] == 'delete') {
        $query = "delete from supplier where supplier_id = " . $_POST['supplier_id'];
        if (mysqli_query($con, $query)) {
            addLog($currentAction, "deleted", "deleted Supplier Id: " . $_POST['supplier_id'], $con);
        }
    }
} catch (exception $e) {
    $_POST["error"] = "Error #1001";
}
echo json_encode($_POST);



function isExistSupplierUpdate($value, $supplier_id, $firm_id, $con)
{
    $exist = true;
    $sql = "select * from supplier where supplier_name='" . $value . "' and supplier_id != " . $supplier_id . " and firm_id = " . $firm_id;
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) == 0) {
        $exist = false;
    }
    return $exist;
}
