<?php
require '../../../libraries/vendor/autoload.php';
include "../../services/config.php";
include "../../services/helperFunctions.php";
include "./commonDebitNoteFunctions.php";

$debitNoteId = $_POST["id"];

session_start();
$user_id = $_SESSION["user_id"];

use Dompdf\Dompdf;



$finalObject =  new \stdClass();

generateDebitNotePdf($debitNoteId, $user_id, $con);

$finalObject->status = "success";
$finalObject->id = $debitNoteId;
$finalObject->user = $user_id;
$finalObject->message = "Debit Note Downloaded successfully!!";


$response = json_encode($finalObject);
echo $response;


function generateDebitNotePdf($debit_note_id, $user_id, $con)
{
    $dompdf = new Dompdf();
    $htmlDebitNote = generateDebitNotePdfHtml($debit_note_id, $con);
    // echo $htmlDebitNote;
    $dompdf->loadHtml($htmlDebitNote);
    $dompdf->setPaper('A4', 'vertical');
    $dompdf->render();
    ob_end_clean();

    // // $dompdf->stream("1", array("Attachment" => false));
    deleteFiles("../temp/*");
    file_put_contents('../temp/Debit_note_' . $debit_note_id . '_' . $user_id . '.pdf', $dompdf->output());
    // // $dompdf->stream("1");
}
