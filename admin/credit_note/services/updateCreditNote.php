<?php

include "../../services/config.php";
include "../../services/helperFunctions.php";
include "../../../libraries/vendor/autoload.php";
include "./commonCreditNoteFunctions.php";

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

    // $sql = "update credit_note set supplier_id=" . $supplier . ",firm_id=" . $firm . ",firm_bank_id=" . $bank . ",credit_note_no=" . $no . ",credit_note_date='" . $date . "',credit_note_ref='" . $reference . "',credit_note_other_ref='" . $otherreference . "',credit_note_place_of_supply='" . $placeofsupply . "',credit_note_sgst_percentage=" . $sgst . ",credit_note_cgst_percentage=" . $cgst . ",credit_note_igst_percentage=" . $igst . ",credit_note_total='" . $total . "',credit_note_pan='" . $pan . "' where credit_note_id = " . $id;
    $sql = "update credit_note set supplier_id=" . $supplier . ",firm_id=" . $firm . ",firm_bank_id=" . $bank . ",credit_note_no=" . $no . ",credit_note_date='" . $date . "',credit_note_ref='" . $reference . "',credit_note_other_ref='" . $otherreference . "',credit_note_place_of_supply='" . $placeofsupply . "',credit_note_sgst_percentage=" . $sgst . ",credit_note_cgst_percentage=" . $cgst . ",credit_note_igst_percentage=" . $igst . ",credit_note_total='" . $total . "' where credit_note_id = " . $id;
    if (mysqli_query($con, $sql)) {
        $deleteQuery = "delete from credit_note_products where credit_note_id = " . $id;
        if (mysqli_query($con, $deleteQuery)) {
            if (insertCreditNoteProducts($id, $rows, $con)) {
                updateEditRequest($id, $user_id, $con);
                generateCreditNotePdf($id, $user_id, $con);
                addLog("Credit Note", "updated", "Credit Note Id: " . $id, $con);
                $finalObject->status = "success";
                $finalObject->id = $id;
                $finalObject->user = $user_id;
                $finalObject->message = "Credit Note updated successfully!!";
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



function insertCreditNoteProducts($credit_note_id, $products, $con)
{
    $created = true;
    foreach ($products as $product) {
        $maximumProductCreditNoteId = getCurrentId("credit_note_products_id", "credit_note_products", $con);
        $sql = "insert into credit_note_products values(" . $maximumProductCreditNoteId . "," . $credit_note_id . ",'" . mysqli_real_escape_string($con, $product["mainText"]) . "','','','" . $product["amount"] . "','','" . $product["hsn"] . "')";
        if (!mysqli_query($con, $sql)) {
            $created = false;
        }
    }
    return $created;
}

function updateEditRequest($credit_note_id, $user_id, $con)
{
    $requestId = getRequestId($credit_note_id, $user_id, $con);
    $sql = "update credit_note_edit_request set credit_note_edit_request_used='true' where credit_note_edit_request_id = " . $requestId;
    mysqli_query($con, $sql);
}

function getRequestId($credit_note_id, $user_id, $con)
{
    $requestId =  0;
    $query = "select credit_note_edit_request_id from credit_note_edit_request where credit_note_id = " . $credit_note_id . " and user_id = " . $user_id . " order by credit_note_edit_request_id desc limit 1";
    $result = $con->query($query);
    $countOfRows = $result->num_rows;
    if ($countOfRows != 0) {
        $row = $result->fetch_assoc();
        $requestId = $row["credit_note_edit_request_id"];
    }
    return $requestId;
}
function generateCreditNotePdf($credit_note_id, $user_id, $con)
{
    $dompdf = new Dompdf();
    $htmlCreditNote = generateCreditNotePdfHtml($credit_note_id, $con);
    // echo $htmlCreditNote;
    $dompdf->loadHtml($htmlCreditNote);
    $dompdf->setPaper('A4', 'vertical');
    $dompdf->render();
    // ob_end_clean();

    // // $dompdf->stream("1", array("Attachment" => false));
    deleteFiles("../temp/*");
    file_put_contents('../temp/Credit_Note_' . $credit_note_id . '_' . $user_id . '.pdf', $dompdf->output());
    // // $dompdf->stream("1");
}


function deleteCreditNote($credit_note_id, $con)
{
    $deleted = true;
    $sql = "delete from credit_note where credit_note_id = " . $credit_note_id;
    if (!mysqli_query($con, $sql)) {
        $deleted = false;
    }
    return $deleted;
}
