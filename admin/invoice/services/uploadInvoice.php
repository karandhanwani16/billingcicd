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
    $maxInvoiceId = getCurrentId("invoice_id", "invoice", $con);
    $sql = "insert into invoice values(" . $supplier . "," . $firm . "," . $bank . "," . $maxInvoiceId . "," . $no . ",'" . $date . "','" . $reference . "','" . $otherreference . "','" . $placeofsupply . "'," . $sgst . "," . $cgst . "," . $igst . ",'" . $total . "')";
    // $sql = "insert into invoice values(" . $supplier . "," . $firm . "," . $bank . "," . $maxInvoiceId . "," . $no . ",'" . $date . "','" . $reference . "','" . $otherreference . "','" . $placeofsupply . "'," . $sgst . "," . $cgst . "," . $igst . ",'" . $total . "','" . $pan . "')";
    if (mysqli_query($con, $sql)) {
        if (createInvoiceProducts($maxInvoiceId, $rows, $con)) {
            generateInvoicePdf($maxInvoiceId, $user_id, $con);
            addLog("Invoice", "upload", "", $con);
            $finalObject->status = "success";
            $finalObject->id = $maxInvoiceId;
            $finalObject->user = $user_id;
            $finalObject->message = "Invoice created successfully!!";
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



function createInvoiceProducts($invoice_id, $products, $con)
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
