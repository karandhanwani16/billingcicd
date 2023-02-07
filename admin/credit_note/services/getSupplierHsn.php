<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

$supplier = $_POST["supplier"];

$query = "select supplier_hsn_code from supplier where supplier_id = " . $supplier;
$result = $con->query($query);
$number_filter_row = $result->num_rows;

$data = "";
if ($number_filter_row > 0) {
    $row = $result->fetch_assoc();
    $data = $row["supplier_hsn_code"];
}
echo json_encode($data);
