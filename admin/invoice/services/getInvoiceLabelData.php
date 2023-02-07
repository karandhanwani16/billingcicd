<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

$id = $_POST["id"];

$query = "select i.invoice_id,f.firm_name,s.supplier_name,fb.firm_bank_name,i.invoice_no,i.invoice_date,i.invoice_other_ref from invoice i,firm_bank fb,firm f,supplier s where i.supplier_id=s.supplier_id and i.firm_id=f.firm_id and i.firm_bank_id = fb.firm_bank_id and i.invoice_id = " . $id;

$result = $con->query($query);
$number_filter_row = $result->num_rows;

$finalObject =  new \stdClass();

if ($number_filter_row > 0) {
    $row = $result->fetch_assoc();
    $finalObject = $row;
}
echo json_encode($finalObject);
