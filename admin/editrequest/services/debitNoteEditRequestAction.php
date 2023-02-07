<?php
require '../../../libraries/vendor/autoload.php';

include "../../../services/env.php";
include "../../services/config.php";
include "../../services/helperFunctions.php";

include "../../debit_note/services/commonDebitNoteFunctions.php";

use Dompdf\Dompdf;


$id = $_POST["id"];
$status = $_POST["status"];

session_start();
$user_id = $_SESSION["user_id"];
$finalObject =  new \stdClass();

try {
    $status = $status == "accept" ? "true" : "decline";
    $sql = "update debit_note_edit_request set debit_note_edit_request_permission_granted='" . $status . "' where debit_note_edit_request_id = " . $id;
    $debit_note_id = getColumnValueFromTable("debit_note_id", "debit_note_edit_request", "debit_note_edit_request_id", $id, $con);

    if (mysqli_query($con, $sql)) {
        // checking if the request is accepted
        if ($status == "true") {
            // get the pdf
            generateDebitNotePdf($debit_note_id, $user_id, $con);
            // preparing the message
            $message = "The Debit Note Edit Request for Debit Note ID : " . $debit_note_id . " was accepted by " . getColumnValueFromTable("user_first_name", "users", "user_id", $user_id, $con) . " " . getColumnValueFromTable("user_last_name", "users", "user_id", $user_id, $con);
            for ($i = 0; $i < count($sendActionsEmails); $i++) {
                sendEmailWithAttachment($sendActionsEmails[$i], "Debit Note Edit Request Accepted", $message, $billingMainEmail, $companyName, '../temp/Debit_note_' . $debit_note_id . '_' . $user_id . '.pdf');
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
