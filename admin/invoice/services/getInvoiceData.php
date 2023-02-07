<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";
include "commonInvoiceFunctions.php";

session_start();
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];


$column = array("i.invoice_id", "f.firm_name", "s.supplier_name", "fb.firm_bank_name", "i.invoice_no", "i.invoice_date", "i.invoice_other_ref");

$query = "select i.invoice_id,f.firm_name,s.supplier_name,fb.firm_bank_name,i.invoice_no,i.invoice_date,i.invoice_other_ref from invoice i,firm_bank fb,firm f,supplier s where i.supplier_id=s.supplier_id and i.firm_id=f.firm_id and i.firm_bank_id = fb.firm_bank_id";

if (isset($_POST["search"]["value"])) {
    $query .= ' and (i.invoice_id like "%' . $_POST["search"]["value"] . '%" or f.firm_name like "%' . $_POST["search"]["value"] . '%" or s.supplier_name like "%' . $_POST["search"]["value"] . '%" or fb.firm_bank_name like "%' . $_POST["search"]["value"] . '%" or i.invoice_no like "%' . $_POST["search"]["value"] . '%" or i.invoice_date like "%' . $_POST["search"]["value"] . '%" or i.invoice_other_ref like "%' . $_POST["search"]["value"] . '%")';
}
if (isset($_POST["order"])) {
    $query .= " ORDER BY " . $column[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
} else {
    $query .= ' ORDER BY i.invoice_id desc';
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

    $sub_array[] = $row['invoice_id'];
    $sub_array[] = "<a href='invoiceUpload.php?id=" . $row['invoice_id'] . "' class='select-btn btn'>Copy</a>";
    $sub_array[] = getEditStatus($row['invoice_id'], $user_id, $con);
    $downloadBtn  = "<div data-id='" . $row['invoice_id'] . "' class='download-btn btn'>Download PDF</div>";
    $deleteBtn = "<div data-id='" . $row['invoice_id'] . "' class='delete-btn btn'>Delete</div>";
    $sub_array[] = $user_type == "super_admin" ? ($downloadBtn . $deleteBtn) : $downloadBtn;
    $sub_array[] = $row['firm_name'];
    $sub_array[] = $row['supplier_name'];
    $sub_array[] = getPrefixtext($row['invoice_id'], $con);
    $sub_array[] = $row['invoice_no'];
    $sub_array[] = $row['invoice_date'];
    $sub_array[] = $row['firm_bank_name'];
    $data[] = $sub_array;
}

function count_all_data($con)
{
    $query = "select * from invoice";
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



function getPrefixtext($invoice_id, $con)
{
    $result = "";
    $query = "select invoice_products_prefix from invoice_products where invoice_id = " . $invoice_id;
    $queryResult = $con->query($query);
    $totalRows = $queryResult->num_rows;
    if ($totalRows != 0) {
        while ($row = $queryResult->fetch_assoc()) {
            $result .= getMultiLineText($row["invoice_products_prefix"]);
        }
    }
    return $result;
}


function getEditStatus($invoice_id, $user_id, $con)
{
    $finalString = "";

    // if edit request exist
    if (isEditRequestExist($invoice_id, $user_id, $con)) {
        // get requestDetail
        $editRequestDetails = getEditRequestDetails($invoice_id, $user_id, $con);

        if ($editRequestDetails["invoice_edit_request_permission_granted"] == "true") {
            // if permission is granted
            if ($editRequestDetails["invoice_edit_request_used"] == "true") {
                // return label as used
                // update: instead show the request button again
                $finalString = "<div class='request-btn btn' data-id='" . $invoice_id . "'>Request Edit</div>";
            } else {
                // return link to edit the invoice
                $finalString = "<a href='editInvoice.php?id=" . $invoice_id . "' class='select-btn btn'>Edit</a>";
            }
        } else if ($editRequestDetails["invoice_edit_request_permission_granted"] == "decline") {
            // return label showing edit requested
            $finalString = "<div class='label requested'>Request Declined</div>";
            $finalString .= "<div class='request-btn btn' data-id='" . $invoice_id . "'>Request Edit</div>";
        } else {
            // return label showing edit requested
            $finalString = "<div class='label requested'>Edit Requested</div>";
        }
    } else {
        // if request is not there for this invoice id and current user
        $finalString = "<div class='request-btn btn' data-id='" . $invoice_id . "'>Request Edit</div>";
    }

    return $finalString;
}


function isEditRequestExist($invoice_id, $user_id, $con)
{
    $isExist = false;
    $query = "select * from invoice_edit_request where invoice_id = " . $invoice_id . " and user_id = " . $user_id;
    $result = $con->query($query);
    $totalRows = $result->num_rows;
    if ($totalRows > 0) {
        $isExist = true;
    }
    return $isExist;
}


function getEditRequestDetails($invoice_id, $user_id, $con)
{
    $details =  new \stdClass();
    $query = "select * from invoice_edit_request where invoice_id = " . $invoice_id . " and user_id = " . $user_id . " order by invoice_edit_request_id desc limit 1";
    $result = $con->query($query);
    $countOfRows = $result->num_rows;
    if ($countOfRows != 0) {
        $row = $result->fetch_assoc();
        $details = $row;
    }
    return $details;
}
