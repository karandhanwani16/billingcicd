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
$isView = $_POST["view"];


// initializing the main array to be converted to excel sheet
$mainArray = array();

// creating header array
$headerArray = array();

array_push($headerArray, 'Month');
array_push($headerArray, 'Year');
array_push($headerArray, 'Firm');
array_push($headerArray, 'Basic Value');
array_push($headerArray, 'GST Value');
array_push($headerArray, 'Total Value');



// initialzing query and file name
$query = "";
$filename = getMonthWord($frommonth) . "_" . $fromyear . "_" . getMonthWord($tomonth) . "_" . $toyear . "_" . $user_id . "_debit_note_report";

if ($firm ==  "") {
    $query = "select MONTH(debit_note_date),YEAR(debit_note_date),debit_note_id,firm_id,debit_note_date from debit_note where " . getPeriodQuery($frommonth, $fromyear, $tomonth, $toyear);
} else {
    $query = "select MONTH(debit_note_date),YEAR(debit_note_date),debit_note_id,firm_id,debit_note_date from debit_note where firm_id = " . $firm . " and (" . getPeriodQuery($frommonth, $fromyear, $tomonth, $toyear) . ")";
}

// echo $query;
$mainArrayWithMonthAndYear = array();

// get firm IDs
$firmArrays = array();

$tempArray = getFirms($firm, $con);
foreach ($tempArray as $firmId) {
    $firmArrays[$firmId] = 0;
}

// making query to be running a big query (optional)
$con->query("SET SQL_BIG_SELECTS=1");
$result = $con->query($query);

// getting every detail into the array
$detailsInArray = array();

// iterating through the debit_note Id to get the debit_note products details in
// MONTH,YEAR,debit_note_id,Invoice_products_value,Invoice_products_percentage,firm_id,sgst,cgst,igst
while ($row = $result->fetch_assoc()) {
    $debit_noteProductsQuery = "select MONTH(i.debit_note_date) as month,YEAR(i.debit_note_date) as year,ip.debit_note_id,ip.debit_note_products_value,ip.debit_note_products_percentage,i.firm_id,i.debit_note_sgst_percentage,i.debit_note_cgst_percentage,i.debit_note_igst_percentage from debit_note_products ip,debit_note i where i.debit_note_id = ip.debit_note_id and i.debit_note_id = " . $row["debit_note_id"];
    $debit_noteProductsResults = $con->query($debit_noteProductsQuery);
    while ($debit_noteProductsRow = $debit_noteProductsResults->fetch_assoc()) {
        $detailsInArray[] = $debit_noteProductsRow;
    }
}

// print_r($detailsInArray);
$yeargrouped = group_by2($detailsInArray, "year");

ksort($yeargrouped);

foreach ($yeargrouped as $year => $yearArray) {
    $monthGrouped = group_by2($yearArray, "month");
    ksort($monthGrouped);
    foreach ($monthGrouped as $month => $monthArray) {
        // print_r($month . " " . $year . "\n");
        // group the data according to the firm_id
        $firmGroupedArray = group_by2($monthArray, "firm_id");
        // print_r($firmGroupedArray);

        // initializing gross_total,gst_total array for the firms
        $totalArray = array();
        // making the total array to keep track of the total
        foreach ($firmGroupedArray as $key => $value) {
            $totalArray[$key] = array("gross_total" => 0, "gst_total" => 0);
        }


        // looping through the array grouped according to the firm id
        foreach ($firmGroupedArray as $key => $value) {
            // key here is firm id
            // getting the single array of firm with the debit_note products
            $currentArray = $firmGroupedArray[$key];
            // grouping the current firm array according to the debit_note id
            $debit_noteGroupedArray = group_by2($currentArray, "debit_note_id");
            // iterating through each of debit_note id firm and getting rounded off total
            foreach ($debit_noteGroupedArray as $debit_noteArrayKey => $debit_noteArrayValue) {
                $singleDebiteNoteProducts = $debit_noteGroupedArray[$debit_noteArrayKey];
                $tempDebiteNoteTotal = 0;
                $tempGstTotal = 0;
                for ($i = 0; $i < count($singleDebiteNoteProducts); $i++) {
                    $rowPercentage = floatval($singleDebiteNoteProducts[$i]["debit_note_products_percentage"]);
                    $rowTotalValue = floatval($singleDebiteNoteProducts[$i]["debit_note_products_value"]);
                    if ($rowPercentage == 0) {
                        $debit_noteProductRowValue = $rowTotalValue;
                    } else {
                        $debit_noteProductRowValue = $rowTotalValue * ($rowPercentage / 100);
                    }
                    $tempDebiteNoteTotal  = $tempDebiteNoteTotal +  $debit_noteProductRowValue;
                }
                $tempGstTotal = ($tempDebiteNoteTotal * (floatval($singleDebiteNoteProducts[0]["debit_note_sgst_percentage"]) / 100)) + ($tempDebiteNoteTotal * (floatval($singleDebiteNoteProducts[0]["debit_note_cgst_percentage"]) / 100)) + ($tempDebiteNoteTotal * (floatval($singleDebiteNoteProducts[0]["debit_note_igst_percentage"]) / 100));
                $totalArray[$key]["gross_total"] = $totalArray[$key]["gross_total"] + $tempDebiteNoteTotal;
                $totalArray[$key]["gst_total"] = $totalArray[$key]["gst_total"] + $tempGstTotal;
            }
        }
        // for each end

        $tempArrayToPutInToMainArray = array();

        foreach ($totalArray as $totalArrayKey => $totalArrayValue) {
            $tempArrayToPutInToMainArray = array();
            $tempArrayToPutInToMainArray[] = getMonthWord($month);
            $tempArrayToPutInToMainArray[] = $year;
            $tempArrayToPutInToMainArray[] = getFirmName($totalArrayKey, $con);
            $tempArrayToPutInToMainArray[] = floatval(formatTo2Decimals($totalArray[$totalArrayKey]["gross_total"]));
            $tempArrayToPutInToMainArray[] = floatval(formatTo2Decimals($totalArray[$totalArrayKey]["gst_total"]));
            $tempArrayToPutInToMainArray[] = floatval(formatTo2Decimals(floatval($totalArray[$totalArrayKey]["gross_total"]) + floatval($totalArray[$totalArrayKey]["gst_total"])));
            $tempArrayToPutInToMainArray[] = $month;
            array_push($mainArrayWithMonthAndYear, $tempArrayToPutInToMainArray);
        }
    }
}


// creating the excel array

// added the header
array_push($mainArray, $headerArray);


// initialize footer totals
$tempBasicTotal = 0;
$tempGstTotal = 0;

array_multisort(
    array_column($mainArrayWithMonthAndYear, 1),
    SORT_ASC,
    array_column($mainArrayWithMonthAndYear, 6),
    SORT_ASC,
    array_column($mainArrayWithMonthAndYear, 5),
    SORT_DESC,
    $mainArrayWithMonthAndYear
);

for ($i = 0; $i < count($mainArrayWithMonthAndYear); $i++) {
    unset($mainArrayWithMonthAndYear[$i][6]);
}
// unset($mainArrayWithMonthAndYear['Gopher']);

for ($i = 0; $i < count($mainArrayWithMonthAndYear); $i++) {
    $currentRow = $mainArrayWithMonthAndYear[$i];
    $mainArray[] = $mainArrayWithMonthAndYear[$i];
    $tempBasicTotal = $tempBasicTotal + $currentRow[3];
    $tempGstTotal = $tempGstTotal + $currentRow[4];
}

// getting the footer with basic,gst and main total values
$footerArray = array();
array_push($footerArray, '');
array_push($footerArray, '');
array_push($footerArray, 'Total');

array_push($footerArray, $tempBasicTotal);
array_push($footerArray, $tempGstTotal);
array_push($footerArray, $tempBasicTotal + $tempGstTotal);

$mainArray[] = $footerArray;


if ($isView == "") {
    // initialize the excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet()->fromArray($mainArray);

    // save the file
    $writer = new Xlsx($spreadsheet);
    $writer->save('../../../assets/files/' . $filename . '.xlsx');

    // delete the rest after saving
    deleteRestFiles('../../../assets/files/' . $filename . '.xlsx');
}
$finalObject->arr = $mainArray;
$finalObject->id = $user_id;
$finalObject->status = "success";
$finalObject->message = "Report downloaded !!!";

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



function getFirms($firm, $con)
{
    $firms = array();
    if ($firm == "") {
        $query = "select firm_id from firm";
    } else {
        $query = "select firm_id from firm where firm_id = " . $firm;
    }
    $result = $con->query($query);
    $totalCount = $result->num_rows;
    if ($totalCount > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($firms, $row['firm_id']);
        }
    }
    return $firms;
}
function getFirmName($firm, $con)
{
    $firmName = "";
    $query = "select firm_name from firm where firm_id = " . $firm;
    $result = $con->query($query);
    $totalCount = $result->num_rows;
    if ($totalCount > 0) {
        $row = $result->fetch_assoc();
        $firmName = $row['firm_name'];
    }
    return $firmName;
}

function getPeriodQuery($frommonth, $fromyear, $tomonth, $toyear)
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
            $finalQuery .= "(MONTH(debit_note_date) = " . $arr[$i][0] . " and YEAR(debit_note_date) = " . $arr[$i][1] . ")";
        } else {
            $finalQuery .= "(MONTH(debit_note_date) = " . $arr[$i][0] . " and YEAR(debit_note_date) = " . $arr[$i][1] . ") or ";
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
