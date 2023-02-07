<?php

require '../../services/config.php';
require '../../services/helperFunctions.php';
require '../../../libraries/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

set_time_limit(3600);
ini_set('memory_limit', '-1');

$finalObject =  new \stdClass();

// getting the user_id
session_start();
$user_id = $_SESSION["user_id"];



//getting the parameters
$frommonth = $_POST["frommonth"];
$fromyear = $_POST["fromyear"];
$tomonth = $_POST["tomonth"];
$toyear = $_POST["toyear"];
$firm = $_POST["firm"];
$supplier = $_POST["supplier"];
$isView = $_POST["view"];


// initializing the main array to be converted to excel sheet
$mainArray = array();

// creating header array
$headerArray = array();

array_push($headerArray, 'Date');
array_push($headerArray, 'Particulars');
array_push($headerArray, 'Voucher Type');
array_push($headerArray, 'Voucher No.');
array_push($headerArray, 'Gross Total');
array_push($headerArray, 'CGST');
array_push($headerArray, 'SGST');
array_push($headerArray, 'IGST');
array_push($headerArray, 'Round Off');
array_push($headerArray, 'Net Total');



// initialzing query and file name
$query = "";
$filename = getMonthWord($frommonth) . "_" . $fromyear . "_" . getMonthWord($tomonth) . "_" . $toyear . "_" . $user_id . "_detailed_report";

// array to get the main IDs
$rawArray = array();

$subrawInvoiceArray = array();

// getting Invoice Details
if ($firm ==  "") {
    $query = "select '' as formatteddate,invoice_id as id,invoice_date as date,supplier_id as supplier,'invoice' as type,invoice_no as no,'' as gross,invoice_sgst_percentage as sgst,invoice_cgst_percentage as cgst,invoice_igst_percentage as igst,'0' as roundoff,invoice_total as total from invoice where " . getPeriodQuery("invoice", $frommonth, $fromyear, $tomonth, $toyear);
} else {
    if ($supplier == "") {
        $query = "select '' as formatteddate,invoice_id as id,invoice_date as date,supplier_id as supplier,'invoice' as type,invoice_no as no,'' as gross,invoice_sgst_percentage as sgst,invoice_cgst_percentage as cgst,invoice_igst_percentage as igst,'0' as roundoff,invoice_total as total from invoice where firm_id = " . $firm . " and (" . getPeriodQuery("invoice", $frommonth, $fromyear, $tomonth, $toyear) . ")";
    } else {
        $query = "select '' as formatteddate,invoice_id as id,invoice_date as date,supplier_id as supplier,'invoice' as type,invoice_no as no,'' as gross,invoice_sgst_percentage as sgst,invoice_cgst_percentage as cgst,invoice_igst_percentage as igst,'0' as roundoff,invoice_total as total from invoice where firm_id = " . $firm . " and supplier_id = " . $supplier . " and (" . getPeriodQuery("invoice", $frommonth, $fromyear, $tomonth, $toyear) . ")";
    }
}
// $con->query("SET SQL_BIG_SELECTS=1");
$result = $con->query($query);
$subrawInvoiceArray = mysqli_fetch_all($result, MYSQLI_ASSOC);

// pushing every entry into raw array
for ($i = 0; $i < count($subrawInvoiceArray); $i++) {
    array_push($rawArray, $subrawInvoiceArray[$i]);
}


// getting Debit Note Details

$subrawDebitNoteArray = array();

if ($firm ==  "") {
    $query = "select '' as formatteddate,debit_note_id as id,debit_note_date as date,supplier_id as supplier,'debit_note' as type,debit_note_no as no,'' as gross,debit_note_sgst_percentage as sgst,debit_note_cgst_percentage as cgst,debit_note_igst_percentage as igst,'0' as roundoff,debit_note_total as total from debit_note where " . getPeriodQuery("debit_note", $frommonth, $fromyear, $tomonth, $toyear);
} else {
    if ($supplier == "") {
        $query = "select '' as formatteddate,debit_note_id as id,debit_note_date as date,supplier_id as supplier,'debit_note' as type,debit_note_no as no,'' as gross,debit_note_sgst_percentage as sgst,debit_note_cgst_percentage as cgst,debit_note_igst_percentage as igst,'0' as roundoff,debit_note_total as total from debit_note where firm_id = " . $firm . " and (" . getPeriodQuery("debit_note", $frommonth, $fromyear, $tomonth, $toyear) . ")";
    } else {
        $query = "select '' as formatteddate,debit_note_id as id,debit_note_date as date,supplier_id as supplier,'debit_note' as type,debit_note_no as no,'' as gross,debit_note_sgst_percentage as sgst,debit_note_cgst_percentage as cgst,debit_note_igst_percentage as igst,'0' as roundoff,debit_note_total as total from debit_note where firm_id = " . $firm . " and supplier_id = " . $supplier . " and (" . getPeriodQuery("debit_note", $frommonth, $fromyear, $tomonth, $toyear) . ")";
    }
}
$result = $con->query($query);
$subrawDebitNoteArray = mysqli_fetch_all($result, MYSQLI_ASSOC);

// pushing every entry into raw array
for ($i = 0; $i < count($subrawDebitNoteArray); $i++) {
    array_push($rawArray, $subrawDebitNoteArray[$i]);
}

// getting Credit Note Details
$subrawCreditNoteArray = array();

if ($firm ==  "") {
    $query = "select '' as formatteddate,credit_note_id as id,credit_note_date as date,supplier_id as supplier,'credit_note' as type,credit_note_no as no,'' as gross,credit_note_sgst_percentage as sgst,credit_note_cgst_percentage as cgst,credit_note_igst_percentage as igst,'0' as roundoff,credit_note_total as total from credit_note where " . getPeriodQuery("credit_note", $frommonth, $fromyear, $tomonth, $toyear);
} else {
    if ($supplier == "") {
        $query = "select '' as formatteddate,credit_note_id as id,credit_note_date as date,supplier_id as supplier,'credit_note' as type,credit_note_no as no,'' as gross,credit_note_sgst_percentage as sgst,credit_note_cgst_percentage as cgst,credit_note_igst_percentage as igst,'0' as roundoff,credit_note_total as total from credit_note where firm_id = " . $firm . " and (" . getPeriodQuery("credit_note", $frommonth, $fromyear, $tomonth, $toyear) . ")";
    } else {
        $query = "select '' as formatteddate,credit_note_id as id,credit_note_date as date,supplier_id as supplier,'credit_note' as type,credit_note_no as no,'' as gross,credit_note_sgst_percentage as sgst,credit_note_cgst_percentage as cgst,credit_note_igst_percentage as igst,'0' as roundoff,credit_note_total as total from credit_note where firm_id = " . $firm . " and supplier_id = " . $supplier . " and (" . getPeriodQuery("credit_note", $frommonth, $fromyear, $tomonth, $toyear) . ")";
    }
}

$result = $con->query($query);
$subrawCreditNoteArray = mysqli_fetch_all($result, MYSQLI_ASSOC);

// pushing every entry into raw array
for ($i = 0; $i < count($subrawCreditNoteArray); $i++) {
    array_push($rawArray, $subrawCreditNoteArray[$i]);
}


// totals variables initialize
$grossAmountTotal = 0;
$cgstTotal = 0;
$sgstTotal = 0;
$igstTotal = 0;
$roundOffTotal = 0;
$mainTotal = 0;

for ($i = 0; $i < count($rawArray); $i++) {
    $id = $rawArray[$i]["id"];
    $type = $rawArray[$i]["type"];
    $rawArray[$i]["formatteddate"] =  formatReportDateString($rawArray[$i]["date"]);
    $rawArray[$i]["supplier"] =  getColumnValueFromTable("supplier_name", "supplier", "supplier_id", $rawArray[$i]["supplier"], $con);
    $rawArray[$i]["type"] =  getVoucherType($rawArray[$i]["type"]);
    $rawArray[$i]["no"] =  $rawArray[$i]["no"] . "/" . getFinancialyear($rawArray[$i]["date"]);
    $grossAmount =  getGrossAmount($type, $id, $con);
    $rawArray[$i]["gross"] =  floatval(formatTo2Decimals($grossAmount));

    $rawArray[$i]["sgst"] =   $rawArray[$i]["sgst"] == "" ? 0 : floatval(formatTo2Decimals($grossAmount * ($rawArray[$i]["sgst"] / 100)));
    $rawArray[$i]["cgst"] =   $rawArray[$i]["cgst"] == "" ? 0 : floatval(formatTo2Decimals($grossAmount * ($rawArray[$i]["cgst"] / 100)));
    $rawArray[$i]["igst"] =   $rawArray[$i]["igst"] == "" ? 0 : floatval(formatTo2Decimals($grossAmount * ($rawArray[$i]["igst"] / 100)));

    // echo $rawArray[$i]["cgst"] . "-" . $rawArray[$i]["sgst"] . "-" . $rawArray[$i]["igst"] . "\n";
    // $rawArray[$i]["cgst"] =  floatval(formatTo2Decimals($grossAmount * ($rawArray[$i]["cgst"] / 100)));
    // $rawArray[$i]["igst"] =  floatval(formatTo2Decimals($grossAmount * ($rawArray[$i]["igst"] / 100)));

    $roundoff = calculateRoundOff($grossAmount, $rawArray[$i]["cgst"], $rawArray[$i]["sgst"], $rawArray[$i]["igst"]);
    $rawArray[$i]["roundoff"] = $roundoff;
    $totalBeforeRoundOff = floatval(formatTo2Decimals($grossAmount + $rawArray[$i]["cgst"] + $rawArray[$i]["sgst"] + $rawArray[$i]["igst"]));
    $rawArray[$i]["total"] = $totalBeforeRoundOff + $roundoff;
    // dropping extra elements in array
    unset($rawArray[$i]["id"]);


    $grossAmountTotal = $grossAmountTotal + $grossAmount;
    $cgstTotal = $cgstTotal + $rawArray[$i]["cgst"];
    $sgstTotal = $sgstTotal + $rawArray[$i]["sgst"];
    $igstTotal = $igstTotal + $rawArray[$i]["igst"];
    $roundOffTotal = $roundOffTotal + $roundoff;
    $mainTotal = $mainTotal + $rawArray[$i]["total"];
}

// // creating the excel array

// added the header
array_push($mainArray, $headerArray);

function date_compare($a, $b)
{
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t1 - $t2;
}
usort($rawArray, 'date_compare');


for ($i = 0; $i < count($rawArray); $i++) {
    unset($rawArray[$i]["date"]);
}

// added the main Array
$lastColumn = count($rawArray) + 2;
for ($i = 0; $i < count($rawArray); $i++) {
    array_push($mainArray, $rawArray[$i]);
}


// print_r($rawArray);


// creating total array
$totalArray = array();

array_push($totalArray, '');
array_push($totalArray, '');
array_push($totalArray, '');
array_push($totalArray, '');
array_push($totalArray, $grossAmountTotal);
array_push($totalArray, $cgstTotal);
array_push($totalArray, $sgstTotal);
array_push($totalArray, $igstTotal);
array_push($totalArray, $roundOffTotal);
array_push($totalArray, $mainTotal);

// added the total
array_push($mainArray, $totalArray);


// print_r($mainArray);


if ($isView == "") {
    $borderStyle = array(
        'borders' => array(
            'outline' => array(
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => array('argb' => 'FF000000'),
            ),
        ),
    );

    // initialize the excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet()->fromArray($mainArray, null, 'A1', true);

    // applying styles
    $currentSheet = $spreadsheet->getActiveSheet();
    // header style
    $currentSheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_CUSTOM);

    // auto resizing the cells through out the sheet
    foreach ($currentSheet->getColumnIterator() as $column) {
        $currentSheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
    //footer
    $currentSheet->getStyle('A' . $lastColumn . ':J' . $lastColumn)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_CUSTOM);


    // save the file
    $writer = new Xlsx($spreadsheet);
    $writer->save('../../../assets/files/' . $filename . '.xlsx');

    // delete the rest after saving
    deleteRestFiles('../../../assets/files/' . $filename . '.xlsx');
}

$finalObject->arr = $mainArray;
$finalObject->id = $user_id;
$finalObject->status = "success";
$finalObject->message = "Report downloaded!!!";

$response = json_encode($finalObject);
echo $response;



function group_by2($array, $key)
{
    $result = array();
    foreach ($array as $element) {
        $result[$element[$key]][] = $element;
    }
    return $result;
}



function deleteRestFiles($filename)
{
    $folder_path = "../../../assets/files";
    $files = glob($folder_path . '/*');
    // Deleting all the files in the list
    foreach ($files as $file) {
        if ($file != $filename) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}




function getPeriodQuery($table, $frommonth, $fromyear, $tomonth, $toyear)
{
    $finalQuery = "";

    $arr = array();
    for ($year = $fromyear; $year <= $toyear; $year++) {
        // if it is current year
        if ($year == $toyear) {
            // if the updated and last sales entry month are not same
            if ($fromyear != $toyear) {
                for ($month = 1; $month <= $tomonth; $month++) {
                    //
                    $sub_array = array();
                    $sub_array[] = $month;
                    $sub_array[] = $year;
                    $arr[] = $sub_array;
                }
            } else {
                for ($month = $frommonth; $month <= $tomonth; $month++) {
                    //
                    $sub_array = array();
                    $sub_array[] = $month;
                    $sub_array[] = $year;
                    $arr[] = $sub_array;
                }
            }
        }
        // if it is updated year
        elseif ($year == $fromyear) {
            for ($month = $frommonth; $month <= 12; $month++) {
                $sub_array = array();
                $sub_array[] = $month;
                $sub_array[] = $year;
                $arr[] = $sub_array;
            }
        } else {
            for ($month = 1; $month <= 12; $month++) {
                $sub_array = array();
                $sub_array[] = $month;
                $sub_array[] = $year;
                $arr[] = $sub_array;
            }
        }
    }

    for ($i = 0; $i < count($arr); $i++) {
        if ($i == (count($arr) - 1)) {
            $finalQuery .= "(MONTH(" . $table . "_date) = " . $arr[$i][0] . " and YEAR(" . $table . "_date) = " . $arr[$i][1] . ")";
        } else {
            $finalQuery .= "(MONTH(" . $table . "_date) = " . $arr[$i][0] . " and YEAR(invoice_date) = " . $arr[$i][1] . ") or ";
        }
    }
    return $finalQuery;
}




function formatNumber($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}



function formatReportDateString($date_string)
{
    $date = date_create($date_string);
    return date_format($date, "d-M-y");
}

function calculateRoundOff($subTotal, $cgst, $sgst, $igst)
{
    $roundOff = 0;
    $totalBeforeRoundOff = $subTotal + $cgst + $sgst + $igst;

    $oldTotal = floatval($totalBeforeRoundOff);
    $finalTotal = round($oldTotal);
    $roundOff = number_format($finalTotal - $oldTotal, 2);
    return $roundOff;
}

function getVoucherType($type)
{
    $voucherType = "";
    switch ($type) {
        case 'debit_note':
            $voucherType = "Debit Note";
            break;
        case 'credit_note':
            $voucherType = "Credit Note";
            break;
        default:
            $voucherType = "Sales";
            break;
    }
    return $voucherType;
}

function getGrossAmount($type, $id, $con)
{
    $grossAmount = 0;
    $query = "select * from " . $type . "_products where " . $type . "_id = " . $id;
    $result = $con->query($query);
    $totalCount = $result->num_rows;

    if ($totalCount != 0) {
        while ($row = $result->fetch_assoc()) {
            if ($type == "invoice") {
                $commissionPercentage = floatval($row['Invoice_products_percentage']);
                $productValue = floatval($row['Invoice_products_value']);
                if ($commissionPercentage == 0) {
                    $grossAmount = $grossAmount + $productValue;
                } else {
                    $grossAmount = $grossAmount + ($productValue * ($commissionPercentage / 100));
                }
            } else {
                $productValue = floatval($row[$type . '_products_value']);
                $grossAmount = $grossAmount + $productValue;
            }
        }
    }
    return $grossAmount;
}
