<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";
include "../../../libraries/vendor/autoload.php";
include "./commonInvoiceFunctions.php";

use Dompdf\Dompdf;

$data = $_POST["data"];
$data =  json_decode($data, true);


session_start();
// checkUrlValidation("admin", "../login.php");
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];


// $images = $data["images"];
$finalObject =  new \stdClass();

//Step 1 : getting all the variables
$id = $data["id"];
$firm = $data["firm"];
$bank = $data["bank"];
$supplier = $data["supplier"];
$date = $data["date"];
$reference = $data["reference"];
$otherreference = $data["otherreference"];
$no = $data["no"];
$gst = $data["gst"];
$sgst = $data["sgst"];
$cgst = $data["cgst"];
$igst = $data["igst"];
$placeofsupply = $data["placeofsupply"];
$total = $data["total"];
$rows = $data["rows"];
// $pan = $data["pan"];

try {

    $sql = "update invoice set supplier_id=" . $supplier . ",firm_id=" . $firm . ",firm_bank_id=" . $bank . ",invoice_no=" . $no . ",invoice_date='" . $date . "',invoice_ref='" . $reference . "',invoice_other_ref='" . $otherreference . "',invoice_place_of_supply='" . $placeofsupply . "',invoice_sgst_percentage=" . $sgst . ",invoice_cgst_percentage=" . $cgst . ",invoice_igst_percentage=" . $igst . ",invoice_total='" . $total . "' where invoice_id = " . $id;
    if (mysqli_query($con, $sql)) {
        $deleteQuery = "delete from invoice_products where invoice_id = " . $id;
        if (mysqli_query($con, $deleteQuery)) {
            if (insertInvoiceProducts($id, $rows, $con)) {
                updateEditRequest($id, $user_id, $con);
                generateInvoicePdf($id, $user_id, $con);
                addLog("Invoice", "updated", "Invoice Id: " . $id, $con);
                $finalObject->status = "success";
                $finalObject->id = $id;
                $finalObject->user = $user_id;
                $finalObject->message = "Invoice updated successfully!!";
            } else {
                $finalObject->status = "error";
                $finalObject->message = "Error #1003";
            }
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Error #1002";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Error #1001";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}

// $image_data = getImageFromBase64($data->image);


$response = json_encode($finalObject);
echo $response;



function insertInvoiceProducts($invoice_id, $products, $con)
{
    $created = true;
    foreach ($products as $product) {
        $maximumProductInvoiceId = getCurrentId("invoice_products_id", "invoice_products", $con);
        $sql = "insert into invoice_products values(" . $maximumProductInvoiceId . "," . $invoice_id . ",'" . mysqli_real_escape_string($con, $product["mainText"]) . "','" . mysqli_real_escape_string($con, $product["prefixText"]) . "','" . mysqli_real_escape_string($con, $product["postfixText"]) . "','" . $product["totalValue"] . "','" . $product["percentage"] . "','" . $product["hsn"] . "')";
        if (!mysqli_query($con, $sql)) {
            $created = false;
        }
    }
    return $created;
}

function updateEditRequest($invoice_id, $user_id, $con)
{
    $requestId = getRequestId($invoice_id, $user_id, $con);
    $sql = "update invoice_edit_request set invoice_edit_request_used='true' where invoice_edit_request_id = " . $requestId;
    mysqli_query($con, $sql);
}

function getRequestId($invoice_id, $user_id, $con)
{
    $requestId =  0;
    $query = "select invoice_edit_request_id from invoice_edit_request where invoice_id = " . $invoice_id . " and user_id = " . $user_id . " order by invoice_edit_request_id desc limit 1";
    $result = $con->query($query);
    $countOfRows = $result->num_rows;
    if ($countOfRows != 0) {
        $row = $result->fetch_assoc();
        $requestId = $row["invoice_edit_request_id"];
    }
    return $requestId;
}
function generateInvoicePdf($invoice_id, $user_id, $con)
{
    $dompdf = new Dompdf();
    $htmlInvoice = generateInvoicePdfHtml($invoice_id, $con);
    // echo $htmlInvoice;
    $dompdf->loadHtml($htmlInvoice);
    $dompdf->setPaper('A4', 'vertical');
    $dompdf->render();
    // ob_end_clean();

    // // $dompdf->stream("1", array("Attachment" => false));
    deleteFiles("../temp/*");
    file_put_contents('../temp/Invoice_' . $invoice_id . '_' . $user_id . '.pdf', $dompdf->output());
    // // $dompdf->stream("1");
}


function deleteInvoice($invoice_id, $con)
{
    $deleted = true;
    $sql = "delete from invoice where invoice_id = " . $invoice_id;
    if (!mysqli_query($con, $sql)) {
        $deleted = false;
    }
    return $deleted;
}
