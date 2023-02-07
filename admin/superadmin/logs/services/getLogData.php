<?php
include "../../../services/config.php";
include "../../../services/helperFunctions.php";

$column = array("log_timestamp", "action_type", "log_action", "log_user", "log_description");

$query = "select log_timestamp,action_type,log_action,log_user,log_description from logs where";

if (isset($_POST["search"]["value"])) {
    $query .= ' log_timestamp like "%' . $_POST["search"]["value"] . '%" or action_type like "%' . $_POST["search"]["value"] . '%" or log_action like "%' . $_POST["search"]["value"] . '%" or log_user like "%' . $_POST["search"]["value"] . '%" or log_description like "%' . $_POST["search"]["value"] . '%"';
}

if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY log_timestamp desc';
}
$query1 = '';

if ($_POST["length"] != -1) {
    $query1 = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}


$result = $con->query($query);
$number_filter_row = $result->num_rows;
$result = $con->query($query . $query1);


// echo $query . $query1;

$data = array();

while ($row = $result->fetch_assoc()) {

    $sub_array = array();
    // $sub_array[] = "<a href='singleproduct.php?id=" . $row['product_id'] . "' class='select-btn'>Select</a>";
    $sub_array[] = $row['log_timestamp'];
    $sub_array[] = $row['action_type'];
    $sub_array[] = $row['log_action'];
    $sub_array[] = $row['log_user'];
    $sub_array[] = $row['log_description'];
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from logs";
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
