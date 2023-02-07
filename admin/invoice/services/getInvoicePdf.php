<?php
require '../../../libraries/vendor/autoload.php';
include "../../services/config.php";
include "../../services/helperFunctions.php";
include "./commonInvoiceFunctions.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$invoiceId = $_POST["id"];

session_start();
$user_id = $_SESSION["user_id"];

use Dompdf\Dompdf;



$finalObject =  new \stdClass();

generateInvoicePdf($invoiceId, $user_id, $con);

$finalObject->status = "success";
$finalObject->id = $invoiceId;
$finalObject->user = $user_id;
$finalObject->message = "Invoice Downloaded successfully!!";


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
