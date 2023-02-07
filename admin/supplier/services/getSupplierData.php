<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

$column = array("s.supplier_id", "f.firm_name", "s.supplier_name", "s.supplier_address", "s.supplier_gst_no", "s.supplier_state", "s.supplier_state_code", "s.supplier_hsn_code");

$query = "select s.supplier_id,f.firm_name,s.supplier_name,s.supplier_address,s.supplier_gst_no,s.supplier_state,s.supplier_state_code,s.supplier_hsn_code from firm f,supplier s where s.firm_id=f.firm_id";

if (isset($_POST["search"]["value"])) {
    $query .= ' and (s.supplier_id like "%' . $_POST["search"]["value"] . '%" or f.firm_name like "%' . $_POST["search"]["value"] . '%" or s.supplier_name like "%' . $_POST["search"]["value"] . '%" or s.supplier_address like "%' . $_POST["search"]["value"] . '%" or s.supplier_gst_no like "%' . $_POST["search"]["value"] . '%" or s.supplier_state like "%' . $_POST["search"]["value"] . '%" or s.supplier_state_code like "%' . $_POST["search"]["value"] . '%" or s.supplier_hsn_code like "%' . $_POST["search"]["value"] . '%")';
}
// like "%' . $_POST["search"]["value"] . '%" or 
if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY s.supplier_id desc';
}
$query1 = '';

if ($_POST["length"] != -1) {
    $query1 = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}


$result = $con->query($query);
$number_filter_row = $result->num_rows;
$result = $con->query($query . $query1);


$data = array();

while ($row = $result->fetch_assoc()) {

    $sub_array = array();
    // $sub_array[] = "<a href='singleproduct.php?id=" . $row['product_id'] . "' class='select-btn'>Select</a>";
    $sub_array[] = $row['supplier_id'];
    $sub_array[] = $row['firm_name'];
    $sub_array[] = $row['supplier_name'];
    $sub_array[] = $row['supplier_address'];
    $sub_array[] = $row['supplier_gst_no'];
    $sub_array[] = $row['supplier_state'];
    $sub_array[] = $row['supplier_state_code'];
    $sub_array[] = $row['supplier_hsn_code'];
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from supplier";
    $result = $con->query($query);
    return $result->num_rows;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($con),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);
