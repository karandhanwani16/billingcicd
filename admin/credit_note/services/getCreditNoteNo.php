<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";

$firm = $_POST["firm"];
$date = $_POST["date"];

$dateRange = get_finacial_year_range($date);
// $dates = getStartAndEndDate($date);

$query = "select max(credit_note_no) as current_max_credit_note_no from credit_note where firm_id = " . $firm . " and credit_note_date BETWEEN '" . $dateRange["start_date"] . "' and '" . $dateRange["end_date"] . "'";
$result = $con->query($query);
$number_filter_row = $result->num_rows;

$data = 0;
if ($number_filter_row > 0) {
    $row = $result->fetch_assoc();
    $data = $row["current_max_credit_note_no"] == null ? 1 : $row["current_max_credit_note_no"] + 1;
}
echo json_encode($data);


// getStartAndEndDate($date)



function get_finacial_year_range($date)
{
    $date = DateTime::createFromFormat("Y-m-d", $date);
    $year = $date->format("Y");
    $month = $date->format("m");
    if ($month < 4) {
        $year = $year - 1;
    }
    $start_date = date('Y-m-d', strtotime(($year) . '-04-01'));
    $end_date = date('Y-m-d', strtotime(($year + 1) . '-03-31'));
    $response = array('start_date' => $start_date, 'end_date' => $end_date);
    return $response;
}
