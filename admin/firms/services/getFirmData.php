<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

$column = array("firm_id", "firm_name", "firm_gst", "firm_address", "firm_state", "firm_state_code");

$query = "select firm_id,firm_name,firm_gst,firm_address,firm_state,firm_state_code from firm where";

if (isset($_POST["search"]["value"])) {
    $query .= '(firm_id like "%' . $_POST["search"]["value"] . '%" or firm_name like "%' . $_POST["search"]["value"] . '%" or firm_gst like "%' . $_POST["search"]["value"] . '%" or firm_address like "%' . $_POST["search"]["value"] . '%" or firm_state like "%' . $_POST["search"]["value"] . '%" or firm_state_code like "%' . $_POST["search"]["value"] . '%")';
}
// like "%' . $_POST["search"]["value"] . '%" or 
if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY firm_id desc';
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
    $sub_array[] = $row['firm_id'];
    $sub_array[] = "<a href='singleFirm.php?id=" . $row['firm_id'] . "' class='select-btn'>Select</a>";
    $sub_array[] = $row['firm_name'];
    $sub_array[] = $row['firm_gst'];
    $sub_array[] = $row['firm_address'];
    $sub_array[] = $row['firm_state'];
    $sub_array[] = $row['firm_state_code'];
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from firm";
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
