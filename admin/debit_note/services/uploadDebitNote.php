<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";
include "../../../libraries/vendor/autoload.php";
include "./commonDebitNoteFunctions.php";

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
    $maxDebitNoteId = getCurrentId("debit_note_id", "debit_note", $con);
    // $sql = "insert into debit_note values(" . $supplier . "," . $firm . "," . $bank . "," . $maxDebitNoteId . "," . $no . ",'" . $date . "','" . $reference . "','" . $otherreference . "','" . $placeofsupply . "'," . $sgst . "," . $cgst . "," . $igst . ",'" . $total . "','" . $pan . "')";
    $sql = "insert into debit_note values(" . $supplier . "," . $firm . "," . $bank . "," . $maxDebitNoteId . "," . $no . ",'" . $date . "','" . $reference . "','" . $otherreference . "','" . $placeofsupply . "'," . $sgst . "," . $cgst . "," . $igst . ",'" . $total . "')";
    if (mysqli_query($con, $sql)) {
        if (createDebitNoteProducts($maxDebitNoteId, $rows, $con)) {
            generateDebitNotePdf($maxDebitNoteId, $user_id, $con);
            addLog("DebitNote", "upload", "", $con);
            $finalObject->status = "success";
            $finalObject->id = $maxDebitNoteId;
            $finalObject->user = $user_id;
            $finalObject->message = "DebitNote created successfully!!";
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



function createDebitNoteProducts($debit_note_id, $products, $con)
{
    $created = true;
    foreach ($products as $product) {
        $maximumProductDebitNoteId = getCurrentId("debit_note_products_id", "debit_note_products", $con);
        $sql = "insert into debit_note_products values(" . $maximumProductDebitNoteId . "," . $debit_note_id . ",'" . mysqli_real_escape_string($con, $product["mainText"]) . "','','','" . $product["amount"] . "','','" . $product["hsn"] . "')";
        if (!mysqli_query($con, $sql)) {
            $created = false;
        }
    }
    return $created;
}




function generateDebitNotePdf($debit_note_id, $user_id, $con)
{
    $dompdf = new Dompdf();
    $htmlDebitNote = generateDebitNotePdfHtml($debit_note_id, $con);
    // echo $htmlDebitNote;
    $dompdf->loadHtml($htmlDebitNote);
    $dompdf->setPaper('A4', 'vertical');
    $dompdf->render();
    // ob_end_clean();

    // // $dompdf->stream("1", array("Attachment" => false));
    deleteFiles("../temp/*");
    file_put_contents('../temp/Debit_Note_' . $debit_note_id . '_' . $user_id . '.pdf', $dompdf->output());
    // // $dompdf->stream("1");
}


function deleteDebitNote($debit_note_id, $con)
{
    $deleted = true;
    $sql = "delete from debit_note where debit_note_id = " . $debit_note_id;
    if (!mysqli_query($con, $sql)) {
        $deleted = false;
    }
    return $deleted;
}
