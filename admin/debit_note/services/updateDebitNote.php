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

    // $sql = "update debit_note set supplier_id=" . $supplier . ",firm_id=" . $firm . ",firm_bank_id=" . $bank . ",debit_note_no=" . $no . ",debit_note_date='" . $date . "',debit_note_ref='" . $reference . "',debit_note_other_ref='" . $otherreference . "',debit_note_place_of_supply='" . $placeofsupply . "',debit_note_sgst_percentage=" . $sgst . ",debit_note_cgst_percentage=" . $cgst . ",debit_note_igst_percentage=" . $igst . ",debit_note_total='" . $total . "',debit_note_pan='" . $pan . "' where debit_note_id = " . $id;
    $sql = "update debit_note set supplier_id=" . $supplier . ",firm_id=" . $firm . ",firm_bank_id=" . $bank . ",debit_note_no=" . $no . ",debit_note_date='" . $date . "',debit_note_ref='" . $reference . "',debit_note_other_ref='" . $otherreference . "',debit_note_place_of_supply='" . $placeofsupply . "',debit_note_sgst_percentage=" . $sgst . ",debit_note_cgst_percentage=" . $cgst . ",debit_note_igst_percentage=" . $igst . ",debit_note_total='" . $total . "' where debit_note_id = " . $id;
    if (mysqli_query($con, $sql)) {
        $deleteQuery = "delete from debit_note_products where debit_note_id = " . $id;
        if (mysqli_query($con, $deleteQuery)) {
            if (insertDebitNoteProducts($id, $rows, $con)) {
                updateEditRequest($id, $user_id, $con);
                generateDebitNotePdf($id, $user_id, $con);
                addLog("Debit Note", "updated", "Debit Note Id: " . $id, $con);
                $finalObject->status = "success";
                $finalObject->id = $id;
                $finalObject->user = $user_id;
                $finalObject->message = "Debit Note updated successfully!!";
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



function insertDebitNoteProducts($debit_note_id, $products, $con)
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

function updateEditRequest($debit_note_id, $user_id, $con)
{
    $requestId = getRequestId($debit_note_id, $user_id, $con);
    $sql = "update debit_note_edit_request set debit_note_edit_request_used='true' where debit_note_edit_request_id = " . $requestId;
    mysqli_query($con, $sql);
}

function getRequestId($debit_note_id, $user_id, $con)
{
    $requestId =  0;
    $query = "select debit_note_edit_request_id from debit_note_edit_request where debit_note_id = " . $debit_note_id . " and user_id = " . $user_id . " order by debit_note_edit_request_id desc limit 1";
    $result = $con->query($query);
    $countOfRows = $result->num_rows;
    if ($countOfRows != 0) {
        $row = $result->fetch_assoc();
        $requestId = $row["debit_note_edit_request_id"];
    }
    return $requestId;
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
