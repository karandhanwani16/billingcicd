<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

session_start();
$user_id = $_SESSION["user_id"];


$column = array("cnr.credit_note_edit_request_id", "cnr.credit_note_id", "u.user_first_name", "cnr.credit_note_edit_request_comment");

$query = "select cnr.credit_note_edit_request_id,cnr.credit_note_id,u.user_first_name,cnr.credit_note_edit_request_comment,u.user_last_name from credit_note_edit_request cnr,users u where cnr.user_id = u.user_id and credit_note_edit_request_permission_granted='false'";

if (isset($_POST["search"]["value"])) {
    $query .= ' and (cnr.credit_note_edit_request_id like "%' . $_POST["search"]["value"] . '%" or cnr.credit_note_id like "%' . $_POST["search"]["value"] . '%" or u.user_first_name like "%' . $_POST["search"]["value"] . '%" or cnr.credit_note_edit_request_comment like "%' . $_POST["search"]["value"] . '%")';
}
if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY cnr.credit_note_edit_request_id desc';
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

    $sub_array[] = $row['credit_note_edit_request_id'];
    $sub_array[] = "<div data-id='" . $row['credit_note_edit_request_id'] . "' class='accept-btn btn'>Accept Request</div><div data-id='" . $row['credit_note_edit_request_id'] . "' class='decline-btn btn'>Decline Request</div>";
    $sub_array[] = "<div data-id='" . $row['credit_note_id'] . "' class='download-btn btn'>Download PDF</div>";
    $sub_array[] = $row['credit_note_id'];
    $sub_array[] = $row['user_first_name'] . " " . $row['user_last_name'];
    $sub_array[] = $row['credit_note_edit_request_comment'];
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from credit_note_edit_request";
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





function getEditStatus($credit_note_id, $user_id, $con)
{
    $finalString = "";

    // if edit request exist
    if (isEditRequestExist($credit_note_id, $user_id, $con)) {
        // get requestDetail
        $editRequestDetails = getEditRequestDetails($credit_note_id, $user_id, $con);

        if ($editRequestDetails["credit_note_edit_request_permission_granted"] == "true") {
            // if permission is granted
            if ($editRequestDetails["credit_note_edit_request_used"] == "true") {
                // return label as used
                // update: instead show the request button again
                $finalString = "<div class='request-btn btn' data-id='" . $credit_note_id . "'>Request Edit</div>";
            } else {
                // return link to edit the credit_note
                $finalString = "<a href='editInvoice.php?id=" . $credit_note_id . "' class='select-btn btn'>Copy</a>";
            }
        } else {
            // return label showing edit requested
            $finalString = "<div class='label requested'>Edit Requested</div>";
        }
    } else {
        // if request is not there for this credit_note id and current user
        $finalString = "<div class='request-btn btn' data-id='" . $credit_note_id . "'>Request Edit</div>";
    }

    return $finalString;
}


function isEditRequestExist($credit_note_id, $user_id, $con)
{
    $isExist = false;
    $query = "select * from credit_note_edit_request where credit_note_id = " . $credit_note_id . " and user_id = " . $user_id;
    $result = $con->query($query);
    $totalRows = $result->num_rows;
    if ($totalRows > 0) {
        $isExist = true;
    }
    return $isExist;
}


function getEditRequestDetails($credit_note_id, $user_id, $con)
{
    $details =  new \stdClass();
    $query = "select * from credit_note_edit_request where credit_note_id = " . $credit_note_id . " and user_id = " . $user_id;
    $result = $con->query($query);
    $countOfRows = $result->num_rows;
    if ($countOfRows != 0) {
        $row = $result->fetch_assoc();
        $details = $row;
    }
    return $details;
}
