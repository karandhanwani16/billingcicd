<?php
include "../../../services/config.php";
include "../../../services/helperFunctions.php";

$column = array("user_id", "user_email", "user_first_name", "user_last_name", "user_type", "user_last_login");

$query = "select user_id,user_email,user_first_name,user_last_name,user_type,user_last_login from users where user_type !='super_admin' ";

if (isset($_POST["search"]["value"])) {
    $query .= ' and (user_id like "%' . $_POST["search"]["value"] . '%" or user_email like "%' . $_POST["search"]["value"] . '%" or user_first_name like "%' . $_POST["search"]["value"] . '%" or user_last_name like "%' . $_POST["search"]["value"] . '%" or user_last_login like "%' . $_POST["search"]["value"] . '%")';
}

if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY user_id';
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
    $sub_array[] = $row['user_id'];
    $sub_array[] = $row['user_email'];
    $sub_array[] = $row['user_first_name'];
    $sub_array[] = $row['user_last_name'];
    $sub_array[] = $row['user_type']=="super_admin"?"Super Admin":"Billing Executive";;
    $sub_array[] = $row['user_last_login'];
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from users";
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
