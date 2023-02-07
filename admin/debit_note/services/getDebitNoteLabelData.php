<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

$id = $_POST["id"];

$query = "select dn.debit_note_id,f.firm_name,s.supplier_name,fb.firm_bank_name,dn.debit_note_no,dn.debit_note_date,dn.debit_note_other_ref from debit_note dn,firm_bank fb,firm f,supplier s where dn.supplier_id=s.supplier_id and dn.firm_id=f.firm_id and dn.firm_bank_id = fb.firm_bank_id and dn.debit_note_id = " . $id;

$result = $con->query($query);
$number_filter_row = $result->num_rows;

$finalObject =  new \stdClass();

if ($number_filter_row > 0) {
    $row = $result->fetch_assoc();
    $finalObject = $row;
}
echo json_encode($finalObject);
