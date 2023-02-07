<?php

require '../../../libraries/vendor/autoload.php';
include "../../services/config.php";
include "../../services/helperFunctions.php";
include "./commonCreditNoteFunctions.php";

$creditNoteId = $_POST["id"];

session_start();
$user_id = $_SESSION["user_id"];

use Dompdf\Dompdf;



$finalObject =  new \stdClass();

generateCreditNotePdf($creditNoteId, $user_id, $con);

$finalObject->status = "success";
$finalObject->id = $creditNoteId;
$finalObject->user = $user_id;
$finalObject->message = "Credit Note Downloaded successfully!!";


$response = json_encode($finalObject);
echo $response;


function generateCreditNotePdf($credit_note_id, $user_id, $con)
{
    $dompdf = new Dompdf();
    $htmlCreditNote = generateCreditNotePdfHtml($credit_note_id, $con);
    // echo $htmlCreditNote;
    $dompdf->loadHtml($htmlCreditNote);
    $dompdf->setPaper('A4', 'vertical');
    $dompdf->render();
    ob_end_clean();

    // // $dompdf->stream("1", array("Attachment" => false));
    deleteFiles("../temp/*");
    file_put_contents('../temp/Credit_note_' . $credit_note_id . '_' . $user_id . '.pdf', $dompdf->output());
    // // $dompdf->stream("1");
}
