<?php
require '../../../libraries/vendor/autoload.php';
// for getting the global Var
include "../../../services/env.php";
include "../../services/config.php";
include "../../services/helperFunctions.php";
include "./commonInvoiceFunctions.php";
$invoiceId = $_POST["id"];

session_start();
$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];

use Dompdf\Dompdf;


$finalObject =  new \stdClass();

if ($user_type == "super_admin") {

    // get the pdf
    generateInvoicePdf($invoice_id, $user_id, $con);

    $query = "delete from invoice_products where invoice_id = " . $invoiceId;
    if (mysqli_query($con, $query)) {
        $query2 = "delete from invoice where invoice_id = " . $invoiceId;
        if (mysqli_query($con, $query2)) {
            addLog("Invoice", "deleted", "deleted Invoice Id: " . $invoiceId, $con);
            // preparing the message
            $message = "The Invoice ID : " . $invoice_id . " was <b>Deleted</b> by " . getColumnValueFromTable("user_first_name", "users", "user_id", $user_id, $con) . " " . getColumnValueFromTable("user_last_name", "users", "user_id", $user_id, $con);
            // sending the message
            for ($i = 0; $i < count($sendActionsEmails); $i++) {
                sendEmailWithAttachment($sendActionsEmails[$i], "Invoice Deleted", $message, $billingMainEmail, $companyName, '../temp/Invoice_' . $invoice_id . '_' . $user_id . '.pdf');
            }

            $finalObject->status = "success";
            $finalObject->id = $invoiceId;
            $finalObject->message = "Invoice Deleted successfully!!";
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Error #1002";
        }
    } else {
        $finalObject->status = "error";
        $finalObject->message = "Error #1001";
    }
} else {
    $finalObject->status = "error";
    $finalObject->message = "You are not Authorized to delete";
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
