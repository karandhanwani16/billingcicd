<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";


$firm = $_POST["firm"];

$query = "select * from supplier where firm_id = " . $firm;
$result = $con->query($query);
$number_filter_row = $result->num_rows;

$data = array();
if ($number_filter_row > 0) {
    while ($row = $result->fetch_assoc()) {

        $sub_array = array();
        $sub_array[] = $row["supplier_id"];
        $sub_array[] = $row["supplier_name"];

        $data[] = $sub_array;
    }
}
echo json_encode($data);
