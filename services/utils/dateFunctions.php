<?php

function getCurrentTimestamp()
{
    // Set the new timezone
    date_default_timezone_set('Asia/Kolkata');
    return date('y-m-d G:i:s');
}


function getTimeDifference($requestedTime)
{
    // Declare and define two dates 
    date_default_timezone_set('Asia/Kolkata');
    $date1 = new DateTime($requestedTime);
    $date2 = $date1->diff(new DateTime());
    $resultJson = json_encode($date2);
    return $resultJson;
}


function getCurrentDate()
{
    date_default_timezone_set('Asia/Kolkata');
    return date('Y-m-d');
}


function calculateRemainingDays($order_date, $product_duration)
{
    $remaining_days = "";
    $ending_date = date('Y-m-d', strtotime("+" . $product_duration . " months", strtotime($order_date)));
    $now = time(); // or your date as well
    $your_date = strtotime($ending_date);
    $datediff =  $your_date - $now;
    $remaining_days = round($datediff / (60 * 60 * 24));
    return $remaining_days;
}

function formatDate($date)
{
    $date = new DateTime($date);
    return date_format($date, "dS F Y");
}
