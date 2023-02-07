<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";
include "./commonCreditNoteFunctions.php";

session_start();
$user_id = $_SESSION["user_id"];


$column = array("cn.credit_note_id", "f.firm_name", "s.supplier_name", "fb.firm_bank_name", "cn.credit_note_no", "cn.credit_note_date", "cn.credit_note_other_ref");

$query = "select cn.credit_note_id,f.firm_name,s.supplier_name,fb.firm_bank_name,cn.credit_note_no,cn.credit_note_date,cn.credit_note_other_ref from credit_note cn,firm_bank fb,firm f,supplier s where cn.supplier_id=s.supplier_id and cn.firm_id=f.firm_id and cn.firm_bank_id = fb.firm_bank_id";

if (isset($_POST["search"]["value"])) {
    $query .= ' and (cn.credit_note_id like "%' . $_POST["search"]["value"] . '%" or f.firm_name like "%' . $_POST["search"]["value"] . '%" or s.supplier_name like "%' . $_POST["search"]["value"] . '%" or fb.firm_bank_name like "%' . $_POST["search"]["value"] . '%" or cn.credit_note_no like "%' . $_POST["search"]["value"] . '%" or cn.credit_note_date like "%' . $_POST["search"]["value"] . '%" or cn.credit_note_other_ref like "%' . $_POST["search"]["value"] . '%")';
}
if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY cn.credit_note_id desc';
}
$query1 = '';

if ($_POST["length"] != -1) {
    $query1 = ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

// echo $query . $query1;

$result = $con->query($query);
$number_filter_row = $result->num_rows;
$result = $con->query($query . $query1);


$data = array();

while ($row = $result->fetch_assoc()) {

    $sub_array = array();
    // $sub_array[] = "<a href='singleproduct.php?id=" . $row['product_id'] . "' class='select-btn'>Select</a>";

    $sub_array[] = $row['credit_note_id'];
    $sub_array[] = "<a href='credit_noteUpload.php?id=" . $row['credit_note_id'] . "' class='select-btn btn'>Copy</a>";
    $sub_array[] = getEditStatus($row['credit_note_id'], $user_id, $con);
    $sub_array[] = "<div data-id='" . $row['credit_note_id'] . "' class='download-btn btn'>Download PDF</div>";
    $sub_array[] = $row['firm_name'];
    $sub_array[] = $row['supplier_name'];
    $sub_array[] = getPrefixtext($row['credit_note_id'], $con);
    $sub_array[] = $row['credit_note_no'];
    $sub_array[] = $row['credit_note_date'];
    $sub_array[] = $row['firm_bank_name'];
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from credit_note";
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



function getPrefixtext($credit_note_id, $con)
{
    $result = "";
    $query = "select credit_note_products_name from credit_note_products where credit_note_id = " . $credit_note_id;
    $queryResult = $con->query($query);
    $totalRows = $queryResult->num_rows;
    if ($totalRows != 0) {
        while ($row = $queryResult->fetch_assoc()) {
            $result .= getMultiLineText($row["credit_note_products_name"]);
        }
    }
    return $result;
}


// function getEditStatus($credit_note_id, $user_id, $con)
// {
//     $finalString = "";
//     $finalString = "<a href='editCreditNote.php?id=" . $credit_note_id . "' class='select-btn btn'>Edit</a>";
//     return $finalString;
// }

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
                // return link to edit the CreditNote
                $finalString = "<a href='editCreditNote.php?id=" . $credit_note_id . "' class='select-btn btn'>Edit</a>";
            }
        } else if ($editRequestDetails["credit_note_edit_request_permission_granted"] == "decline") {
            // return label showing edit requested
            $finalString = "<div class='label requested'>Request Declined</div>";
            $finalString .= "<div class='request-btn btn' data-id='" . $credit_note_id . "'>Request Edit</div>";
        } else {
            // return label showing edit requested
            $finalString = "<div class='label requested'>Edit Requested</div>";
        }
    } else {
        // if request is not there for this CreditNote id and current user
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
    $query = "select * from credit_note_edit_request where credit_note_id = " . $credit_note_id . " and user_id = " . $user_id . " order by credit_note_edit_request_id desc limit 1";
    $result = $con->query($query);
    $countOfRows = $result->num_rows;
    if ($countOfRows != 0) {
        $row = $result->fetch_assoc();
        $details = $row;
    }
    return $details;
}
