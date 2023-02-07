<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

$column = array("location_id", "location_name","location_active");

$query = "select location_id,location_name,location_active from location where";

if (isset($_POST["search"]["value"])) {
    $query .= '(location_id like "%' . $_POST["search"]["value"] . '%" or location_name like "%' . $_POST["search"]["value"] . '%" or location_active like "%' . $_POST["search"]["value"] . '%" )';
}
// like "%' . $_POST["search"]["value"] . '%" or 
if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY location_id';
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
    $sub_array[] = $row['location_id'];
    $sub_array[] = $row['location_name'];
    $sub_array[] = $row['location_active']=="true"?"yes":"no";
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from location";
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
