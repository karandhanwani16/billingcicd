<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

$id = $_POST["id"];

$query = "select cn.credit_note_id,f.firm_name,s.supplier_name,fb.firm_bank_name,cn.credit_note_no,cn.credit_note_date,cn.credit_note_other_ref from credit_note cn,firm_bank fb,firm f,supplier s where cn.supplier_id=s.supplier_id and cn.firm_id=f.firm_id and cn.firm_bank_id = fb.firm_bank_id and cn.credit_note_id = " . $id;

$result = $con->query($query);
$number_filter_row = $result->num_rows;

$finalObject =  new \stdClass();

if ($number_filter_row > 0) {
    $row = $result->fetch_assoc();
    $finalObject = $row;
}
echo json_encode($finalObject);
