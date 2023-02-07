<?php
require '../../../libraries/vendor/autoload.php';

include "../../../services/env.php";
include "../../services/config.php";
include "../../services/helperFunctions.php";

include "../../credit_note/services/commonCreditNoteFunctions.php";

use Dompdf\Dompdf;


$id = $_POST["id"];
$status = $_POST["status"];

session_start();
$user_id = $_SESSION["user_id"];
$finalObject =  new \stdClass();

try {
    $status = $status == "accept" ? "true" : "decline";
    $sql = "update credit_note_edit_request set credit_note_edit_request_permission_granted='" . $status . "' where credit_note_edit_request_id = " . $id;
    $credit_note_id = getColumnValueFromTable("credit_note_id", "credit_note_edit_request", "credit_note_edit_request_id", $id, $con);
    if (mysqli_query($con, $sql)) {
        // checking if the request is accepted
        if ($status == "true") {
            // get the pdf
            generateCreditNotePdf($credit_note_id, $user_id, $con);
            // preparing the message
            $message = "The Credit Note Edit Request for Credit Note ID : " . $credit_note_id . " was accepted by " . getColumnValueFromTable("user_first_name", "users", "user_id", $user_id, $con) . " " . getColumnValueFromTable("user_last_name", "users", "user_id", $user_id, $con);
            for ($i = 0; $i < count($sendActionsEmails); $i++) {
                sendEmailWithAttachment($sendActionsEmails[$i], "Credit Note Edit Request Accepted", $message, $billingMainEmail, $companyName, '../temp/Credit_note_' . $credit_note_id . '_' . $user_id . '.pdf');
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


function generateCreditNotePdf($credit_note_id, $user_id, $con)
{
    $dompdf = new Dompdf();
    $htmlCreditNote = generateCreditNotePdfHtml($credit_note_id, $con);
    $dompdf->loadHtml($htmlCreditNote);
    $dompdf->setPaper('A4', 'vertical');
    $dompdf->render();
    ob_end_clean();
    deleteFiles("../temp/*");
    file_put_contents('../temp/Credit_note_' . $credit_note_id . '_' . $user_id . '.pdf', $dompdf->output());
}
