<?php
require '../../../libraries/vendor/autoload.php';

include "../../../services/env.php";
include "../../services/config.php";
include "../../services/helperFunctions.php";

include "../../invoice/services/commonInvoiceFunctions.php";

use Dompdf\Dompdf;

$id = $_POST["id"];
$status = $_POST["status"];

session_start();
$user_id = $_SESSION["user_id"];
$finalObject =  new \stdClass();

try {
    $status = $status == "accept" ? "true" : "decline";
    $sql = "update invoice_edit_request set invoice_edit_request_permission_granted='" . $status . "' where invoice_edit_request_id = " . $id;
    $invoice_id = getColumnValueFromTable("invoice_id", "invoice_edit_request", "invoice_edit_request_id", $id, $con);
    if (mysqli_query($con, $sql)) {
        if ($status == "true") {
            // get the pdf
            generateInvoicePdf($invoice_id, $user_id, $con);
            // preparing the message
            $message = "The Invoice Edit Request for Invoice ID : " . $invoice_id . " was accepted by " . getColumnValueFromTable("user_first_name", "users", "user_id", $user_id, $con) . " " . getColumnValueFromTable("user_last_name", "users", "user_id", $user_id, $con);
            for ($i = 0; $i < count($sendActionsEmails); $i++) {
                sendEmailWithAttachment($sendActionsEmails[$i], "Invoice Edit Request Accepted", $message, $billingMainEmail, $companyName, '../temp/Invoice_' . $invoice_id . '_' . $user_id . '.pdf');
            }
        }
        addLog("Edit Request", $status == "true" ? "Accepted" : "Declined", "Request Created by " . $user_id . " for Edit Request Id " . $id . " by " . $user_id, $con);
        $finalObject->status = "success";
        $finalObject->message = $status == "true" ? "Request Accepted!" : "Request Declined!";
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Error #1001";
    }
} catch (Exception $e) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1000";
}

$response = json_encode($finalObject);
echo $response;



function generateInvoicePdf($invoice_id, $user_id, $con)
{
    $dompdf = new Dompdf();
    $htmlInvoice = generateInvoicePdfHtml($invoice_id, $con);
    // echo $htmlInvoice;
    $dompdf->loadHtml($htmlInvoice);
    $dompdf->setPaper('A4', 'vertical');
    $dompdf->render();
    deleteFiles("../temp/*");
    file_put_contents('../temp/Invoice_' . $invoice_id . '_' . $user_id . '.pdf', $dompdf->output());
}
