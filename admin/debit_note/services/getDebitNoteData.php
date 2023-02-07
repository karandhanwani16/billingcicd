<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";
include "./commonDebitNoteFunctions.php";

session_start();
$user_id = $_SESSION["user_id"];


$column = array("dn.debit_note_id", "f.firm_name", "s.supplier_name", "fb.firm_bank_name", "dn.debit_note_no", "dn.debit_note_date", "dn.debit_note_other_ref");

$query = "select dn.debit_note_id,f.firm_name,s.supplier_name,fb.firm_bank_name,dn.debit_note_no,dn.debit_note_date,dn.debit_note_other_ref from debit_note dn,firm_bank fb,firm f,supplier s where dn.supplier_id=s.supplier_id and dn.firm_id=f.firm_id and dn.firm_bank_id = fb.firm_bank_id";

if (isset($_POST["search"]["value"])) {
    $query .= ' and (dn.debit_note_id like "%' . $_POST["search"]["value"] . '%" or f.firm_name like "%' . $_POST["search"]["value"] . '%" or s.supplier_name like "%' . $_POST["search"]["value"] . '%" or fb.firm_bank_name like "%' . $_POST["search"]["value"] . '%" or dn.debit_note_no like "%' . $_POST["search"]["value"] . '%" or dn.debit_note_date like "%' . $_POST["search"]["value"] . '%" or dn.debit_note_other_ref like "%' . $_POST["search"]["value"] . '%")';
}
if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY dn.debit_note_id desc';
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

    $sub_array[] = $row['debit_note_id'];
    $sub_array[] = "<a href='debit_noteUpload.php?id=" . $row['debit_note_id'] . "' class='select-btn btn'>Copy</a>";
    $sub_array[] = getEditStatus($row['debit_note_id'], $user_id, $con);
    $sub_array[] = "<div data-id='" . $row['debit_note_id'] . "' class='download-btn btn'>Download PDF</div>";
    $sub_array[] = $row['firm_name'];
    $sub_array[] = $row['supplier_name'];
    $sub_array[] = getPrefixtext($row['debit_note_id'], $con);
    $sub_array[] = $row['debit_note_no'];
    $sub_array[] = $row['debit_note_date'];
    $sub_array[] = $row['firm_bank_name'];
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from debit_note";
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



function getPrefixtext($debit_note_id, $con)
{
    $result = "";
    $query = "select debit_note_products_name from debit_note_products where debit_note_id = " . $debit_note_id;
    $queryResult = $con->query($query);
    $totalRows = $queryResult->num_rows;
    if ($totalRows != 0) {
        while ($row = $queryResult->fetch_assoc()) {
            $result .= getMultiLineText($row["debit_note_products_name"]);
        }
    }
    return $result;
}


// function getEditStatus($debit_note_id, $user_id, $con)
// {
//     $finalString = "";
//     $finalString = "<a href='editDebitNote.php?id=" . $debit_note_id . "' class='select-btn btn'>Edit</a>";
//     return $finalString;
// }

function getEditStatus($debit_note_id, $user_id, $con)
{
    $finalString = "";

    // if edit request exist
    if (isEditRequestExist($debit_note_id, $user_id, $con)) {
        // get requestDetail
        $editRequestDetails = getEditRequestDetails($debit_note_id, $user_id, $con);

        if ($editRequestDetails["debit_note_edit_request_permission_granted"] == "true") {
            // if permission is granted
            if ($editRequestDetails["debit_note_edit_request_used"] == "true") {
                // return label as used
                // update: instead show the request button again
                $finalString = "<div class='request-btn btn' data-id='" . $debit_note_id . "'>Request Edit</div>";
            } else {
                // return link to edit the DebitNote
                $finalString = "<a href='editDebitNote.php?id=" . $debit_note_id . "' class='select-btn btn'>Edit</a>";
            }
        } else if ($editRequestDetails["debit_note_edit_request_permission_granted"] == "decline") {
            // return label showing edit requested
            $finalString = "<div class='label requested'>Request Declined</div>";
            $finalString .= "<div class='request-btn btn' data-id='" . $debit_note_id . "'>Request Edit</div>";
        } else {
            // return label showing edit requested
            $finalString = "<div class='label requested'>Edit Requested</div>";
        }
    } else {
        // if request is not there for this DebitNote id and current user
        $finalString = "<div class='request-btn btn' data-id='" . $debit_note_id . "'>Request Edit</div>";
    }

    return $finalString;
}


function isEditRequestExist($debit_note_id, $user_id, $con)
{
    $isExist = false;
    $query = "select * from debit_note_edit_request where debit_note_id = " . $debit_note_id . " and user_id = " . $user_id;
    $result = $con->query($query);
    $totalRows = $result->num_rows;
    if ($totalRows > 0) {
        $isExist = true;
    }
    return $isExist;
}


function getEditRequestDetails($debit_note_id, $user_id, $con)
{
    $details =  new \stdClass();
    $query = "select * from debit_note_edit_request where debit_note_id = " . $debit_note_id . " and user_id = " . $user_id . " order by debit_note_edit_request_id desc limit 1";
    $result = $con->query($query);
    $countOfRows = $result->num_rows;
    if ($countOfRows != 0) {
        $row = $result->fetch_assoc();
        $details = $row;
    }
    return $details;
}
