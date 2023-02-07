<?php



function generateInvoicePdfHtml($invoice_id, $con)
{

    $invoiceDetails = getTableDetails("invoice", "invoice_id", $invoice_id, $con);
    $supplierDetails = getTableDetails("supplier", "supplier_id", $invoiceDetails["supplier_id"], $con);

    $financialYear = getFinancialyear($invoiceDetails["invoice_date"]);
    $invoiceProductsDetails =  getTableDetails("invoice_products", "invoice_id", $invoice_id, $con);

    $firmDetails = getTableDetails("firm", "firm_id", $invoiceDetails["firm_id"], $con);
    $bankDetails = getTableDetails("firm_bank", "firm_bank_id", $invoiceDetails["firm_bank_id"], $con);

    $finalString = "<!DOCTYPE html><html lang='en'><head> <meta charset='UTF-8'> <meta http-equiv='X-UA-Compatible' content='IE=edge'> <meta name='viewport' content='width=device-width, initial-scale=1.0'> <style>*{box-sizing: border-box;padding: 0; margin: 0;font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 0.95rem;}ul{list-style-type:none;}body{padding:24px;padding-top:0;}table{border-collapse: collapse;}table.header{width: 100%;}td{padding: 2px;} .header-label{font-size: 0.85rem; margin-bottom: 6px;}.header-data{font-weight: 600;}</style></head><body>";
    $finalString .= " <table style='padding-top: 16px;padding-bottom: 8px;width:100%;'> <tr> <td style='width: 33%;text-align:center;'></td><td style='width: 33%;text-align:center;'> <h3>TAX INVOICE</h3> </td><td style='width: 33%;text-align:center;'> <p>(DUPLICATE FOR BUYER)</p></td></tr></table>";

    // trial
    $finalString .= "<table style='border:1px solid #000;' class='header'> <tr> <td rowspan='2' style='width: 50%;border-right:1px solid #000;border-bottom:1px solid #000;'> <p> <b>" . $firmDetails["firm_name"] . "</b> </p><p>" . $firmDetails["firm_address"] . "</p><p> GSTIN/UIN: " . $firmDetails["firm_gst"] . " </p><p> PAN/IT NO: " . getPanFromGST($firmDetails["firm_gst"]) . " </p><p> State Name: " . $firmDetails["firm_state"] . " </p><p> Code: " . $firmDetails["firm_state_code"] . " </p></td>";
    // invoice no & etc
    // $finalString .= "<td style='border-bottom:1px solid #000;border-right:1px solid #000;'> <div class='header-label'>Invoice No.</div><div class='header-data'>" . $invoiceDetails["invoice_no"] . "/" . $financialYear . "</div></td><td style='border-bottom:1px solid #000;'> <div class='header-label'>Dated</div><div class='header-data'>" . date("j-M-y", strtotime($invoiceDetails["invoice_date"])) . "</div></td></tr><tr> <td style='border-bottom:1px solid #000;'> <div class='header-label'>PO No.</div><div class='header-data'>" . $invoiceDetails["invoice_other_ref"] . "</div></td><td style='border-bottom:1px solid #000;border-left:1px solid #000;'> <div class='header-label'>PAN No.</div><div class='header-data'>" . $invoiceDetails["invoice_pan"] . "</div></td></tr>";
    $finalString .= "<td style='border-bottom:1px solid #000;border-right:1px solid #000;'> <div class='header-label'>Invoice No.</div><div class='header-data'>" . $financialYear . "/" . $invoiceDetails["invoice_no"] . "</div></td><td style='border-bottom:1px solid #000;'> <div class='header-label'>Dated</div><div class='header-data'>" . date("j-M-y", strtotime($invoiceDetails["invoice_date"])) . "</div></td></tr><tr> <td colspan='2' style='border-bottom:1px solid #000;'> <div class='header-label'>PO No.</div><div class='header-data'>" . $invoiceDetails["invoice_other_ref"] . "</div></td></tr>";
    //supplier details
    $finalString .= "<tr> <td style='width: 50%;border-right:1px solid #000;'> <p>Buyer (Bill to)</p><p> <b>" . $supplierDetails["supplier_name"] . "</b> </p><p>" . $supplierDetails["supplier_address"] . "</p><p> GSTIN/UIN : " . $supplierDetails["supplier_gst_no"] . " </p><p> PAN/IT NO: " . getPanFromGST($supplierDetails["supplier_gst_no"]) . " </p><p> State Name: " . $supplierDetails["supplier_state"] . " </p><p> Code: " . $supplierDetails["supplier_state_code"] . " </p><p> Place of Supply: " . $invoiceDetails["invoice_place_of_supply"] . " </p></td><td colspan='2' style='border-bottom: 1px solid #000;border-right: 1px solid #000;'><p style='margin-bottom:12px;'><b>Reference</b></p><p>" . getMultiLineText($invoiceDetails["invoice_ref"]) . "</p></td></tr></table>";


    // particulars start

    // particulars header
    $finalString .= "<table style='max-width: 100%;min-width: 100%;border-left: 1px solid #000;border-right: 1px solid #000;min-height: 320px;'> <tr> <th style='border-bottom: 1px solid #000;border-right: 1px solid #000; width:5%;'>Sr No.</th> <th style='border-bottom: 1px solid #000;border-right: 1px solid #000; width:65%;'>Particulars</th> <th style='border-bottom: 1px solid #000;border-right: 1px solid #000;'>HSN/SAC</th> <th style='border-bottom: 1px solid #000;'>Amount</th> </tr>";

    // particulars body
    $finalString .= "<tbody style='border-bottom: 1px solid #000;'>";

    // print_r($invoiceProductsDetails);

    $subTotal = 0;
    $rowTotal = is_null($invoiceProductsDetails) ? 0 : count($invoiceProductsDetails);
    for ($i = 0; $i < $rowTotal; $i++) {
        $finalString .= "<tr> <td style='border-right: 1px solid #000;vertical-align: top;text-align:center;'>" . ($i + 1) . "</td><td style='border-right: 1px solid #000;padding: 2px 18px;'>";
        // main charges
        $finalString .= "<p><b>" . $invoiceProductsDetails[$i]["Invoice_products_name"] . "</b></p>";
        // prefix
        $finalString .= "<p>" . getMultiLineText($invoiceProductsDetails[$i]["Invoice_products_prefix"]) . "</p>";
        // percentage
        $percentage = floatval($invoiceProductsDetails[$i]["Invoice_products_percentage"]);
        if ($percentage != 0) {
            // actual Value
            $finalString .= "<p>VALUE:- " . moneyFormatIndia($invoiceProductsDetails[$i]["Invoice_products_value"]) . "/-</p>";
            // percentage
            $finalString .= "<p>@" . $percentage . "%</p>";
        }
        // postfix
        $finalString .= "<p>" . getMultiLineText($invoiceProductsDetails[$i]["Invoice_products_postfix"]) . "</p>";
        $finalString .= "</td>";
        // HSN
        $finalString .= "<td style='border-right: 1px solid #000;vertical-align: top;text-align:center;'>" . $invoiceProductsDetails[$i]["Invoice_products_hsn"] . "</td>";
        // row total
        if ($percentage != 0) {
            $rowAmountTotal = floatval($invoiceProductsDetails[$i]["Invoice_products_value"]) * (floatval($percentage) / 100);
        } else {
            $rowAmountTotal = floatval($invoiceProductsDetails[$i]["Invoice_products_value"]);
        }
        $finalString .= "<td style='vertical-align: top;text-align:right;'>" . moneyFormatIndia($rowAmountTotal) . "</td></tr>";
        // calculations
        $subTotal = $subTotal + $rowAmountTotal;
    }
    // calculations
    $cGstPercentage = $invoiceDetails["invoice_cgst_percentage"];
    $sGstPercentage = $invoiceDetails["invoice_sgst_percentage"];
    $iGstPercentage = $invoiceDetails["invoice_igst_percentage"];

    $cgst = $subTotal * ($cGstPercentage / 100);
    $sgst = $subTotal * ($sGstPercentage / 100);
    $igst = $subTotal * ($iGstPercentage / 100);

    $totalBeforeRoundOff = $subTotal + $cgst + $sgst + $igst;

    $oldTotal = floatval($totalBeforeRoundOff);
    $finalTotal = round($oldTotal);
    $roundoff = number_format($finalTotal - $oldTotal, 2);


    $finalTotal = $totalBeforeRoundOff + $roundoff;


    // dummy content
    // $finalString .= " <tr> <td style='border-right: 1px solid #000;'>1</td><td style='border-right: 1px solid #000;padding: 2px 18px;'> <p><b>Service Charges</b></p><p>PRIMARY BILLING FEB'22</p><p>VALUE:- 2,15,74,058.24/-</p><p>@1%</p></td><td style='border-right: 1px solid #000;'>998599</td><td>2,15,741.00</td></tr>";

    // gst and total
    $finalString .= " <tr> <td style='border-right: 1px solid #000;'></td><td style='border-right: 1px solid #000;padding: 2px 18px;text-align: right;'> CGST </td><td style='border-right: 1px solid #000;'></td><td style='text-align:right;'>" . moneyFormatIndia($cgst) . "</td></tr><tr> <td style='border-right: 1px solid #000;'></td><td style='border-right: 1px solid #000;padding: 2px 18px;text-align: right;'> SGST </td><td style='border-right: 1px solid #000;'></td><td style='text-align:right;'>" . moneyFormatIndia($sgst) . "</td></tr><tr> <td style='border-right: 1px solid #000;'></td><td style='border-right: 1px solid #000;padding: 2px 18px;text-align: right;'> IGST </td><td style='border-right: 1px solid #000;'></td><td style='text-align:right;'>" . moneyFormatIndia($igst) . "</td></tr><tr> <td style='border-right: 1px solid #000;'></td><td style='border-right: 1px solid #000;padding: 2px 18px;text-align: left;'> less: <b>Round Off</b> </td><td style='border-right: 1px solid #000;'></td><td style='text-align:right;'>" . number_format($roundoff, 2) . "</td></tr>";

    $finalString .= "</tbody>";

    // particuklars footer
    $finalString .= " <tfoot style='border-bottom: 1px solid #000;'><tr> <td style='border-right: 1px solid #000;'></td><td style='border-right: 1px solid #000;padding: 2px 18px;text-align: right;'> Total </td><td style='border-right: 1px solid #000;'></td><td style='text-align:right;'>Rs. " . moneyFormatIndia($finalTotal) . "</td></tr></tfoot></table>";

    // bottom table
    $finalString .= "<table style='min-width: 100%;border-left: 1px solid #000;border-right: 1px solid #000;border-bottom: 1px solid #000;'> <tr> <td style='width: 35%;text-align: left;'>Amount Chargeable (In words)</td><td style='width: 65%;text-align: right;'>E. & O.E</td></tr><tr> <td colspan='2'> <p><b>INR " . convertNumberToWords($finalTotal) . "</b></p></td></tr></table>";

    // tax details
    $finalString .= "<table style='font-size: 0.8rem; width: 100%;border-left: 1px solid #000;border-right: 1px solid #000;border-bottom: 1px solid #000;'> <tr style='border-bottom: 1px solid #000;'> <td rowspan='2' style='width: 50%; border-right: 1px solid #000;border-bottom: 1px solid #000;'>HSN/SAC</td><td rowspan='2' style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Taxable Value</td><td colspan='2' style='border-bottom: 1px solid #000;border-right: 1px solid #000;'>Central Tax</td><td colspan='2' style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>State Tax</td><td colspan='2' style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Interstate Tax</td><td style='border-bottom: 1px solid #000;' rowspan='2'>Total Tax Amount</td></tr><tr style='border-bottom: 1px solid #000;'> <td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Rate</td><td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Amount</td><td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Rate</td><td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Amount</td><td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Rate</td><td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Amount</td></tr>";
    // $finalString .= "<table style='font-size: 0.8rem; width: 100%;border-left: 1px solid #000;border-right: 1px solid #000;border-bottom: 1px solid #000;'> <tr style='border-bottom: 1px solid #000;'> <td rowspan='2' style='width: 50%; border-right: 1px solid #000;border-bottom: 1px solid #000;'>HSN/SAC</td><td rowspan='2' style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Taxable Value</td><td colspan='2' style='border-bottom: 1px solid #000;border-right: 1px solid #000;'>Central Tax</td><td colspan='2' style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>State Tax</td><td style='border-bottom: 1px solid #000;' rowspan='2'>Total Tax Amount</td></tr><tr style='border-bottom: 1px solid #000;'> <td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Rate</td><td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Amount</td><td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Rate</td><td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>Amount</td></tr>";

    // tax row
    $taxTotal = $cgst + $sgst + $igst;
    // hsn
    $finalString .= "<tr style='border-bottom: 1px solid #000;'> <td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>" . $supplierDetails["supplier_hsn_code"] . "</td>";
    // sub total
    $finalString .= "<td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>" . moneyFormatIndia($subTotal) . "</td>";
    // cgst
    $finalString .= "<td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>" . $cGstPercentage . "%</td><td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>" . moneyFormatIndia($cgst) . "</td>";
    //sgst 
    $finalString .= "<td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>" . $sGstPercentage . "%</td><td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>" . moneyFormatIndia($sgst) . "</td>";
    // igst 
    $finalString .= "<td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>" . $iGstPercentage . "%</td><td style='border-right: 1px solid #000;border-bottom: 1px solid #000;'>" . moneyFormatIndia($igst) . "</td>";
    // total tax amount
    $finalString .= "<td style='border-bottom: 1px solid #000;'>" . moneyFormatIndia($taxTotal) . "</td>";

    // last row
    $finalString .= "</tr><tr style='border-bottom: 1px solid #000;'> <td style='border-right: 1px solid #000;'>Total</td><td style='border-right: 1px solid #000;'>" . moneyFormatIndia($subTotal) . "</td>";
    // cgst total
    $finalString .= "<td style='border-right: 1px solid #000;'></td><td style='border-right: 1px solid #000;'>" . moneyFormatIndia($cgst) . "</td>";
    // sgst total
    $finalString .= "<td style='border-right: 1px solid #000;'></td><td style='border-right: 1px solid #000;'>" . moneyFormatIndia($sgst) . "</td>";
    // igst total
    $finalString .= "<td style='border-right: 1px solid #000;'></td><td style='border-right: 1px solid #000;'>" . moneyFormatIndia($igst) . "</td>";
    // final row final total
    $finalString .= "<td>" . moneyFormatIndia($taxTotal) . "</td></tr></table>";

    // tax in words
    $finalString .= "<table style='width: 100%;border-left: 1px solid #000;border-right: 1px solid #000;'> <tr> <td style='padding:20px 0;'> <p>Tax Amount (in words) : </p></td><td> <p><b>INR " . convertNumberToWords($taxTotal) . "</b></p></td></tr></table>";

    // bank details and end

    $finalString .= "<table style='width: 100%;border-left: 1px solid #000;border-bottom: 1px solid #000;border-right: 1px solid #000;'> <tr> <td style='width: 55%;border-top:1px solid #000;' colspan='2'> <p>Company's Bank Details:</p></td><td style='width: 45%;border-top:1px solid #000;border-left:1px solid #000;border-bottom:1px solid #000;' rowspan='4'> <p style='text-align:right;'> <b> for " . $firmDetails["firm_name"] . " </b> </p><p style='text-align: right;margin-top: 48px;'> Authorised Signatory </p></td></tr>";
    $finalString .= "<tr> <td> <p>Bank Name: </p></td><td> <p>" . $bankDetails["firm_bank_name"] . " - " . $bankDetails["firm_bank_account_no"] . "</p></td></tr>";
    $finalString .= "<tr> <td> <p>A/c No.: </p></td><td> <p>ICICI " . $bankDetails["firm_bank_account_no"] . "</p></td></tr>";
    $finalString .= "<tr> <td> <p>Branch & IFS Code: </p></td><td> <p>" . $bankDetails["firm_bank_branch_name"] . "&" . $bankDetails["firm_bank_ifsc"] . "</p></td></tr>";
    $finalString .= "</table>";
    $finalString .= "<p style='width: 100%;text-align: center;margin-top: 12px;'>This is Computer Generated Invoice</p></body></html>";
    // $finalString .= "<tr> <td></td><td style='border-left: 1px solid #000;border-top: 1px solid #000;' colspan='2'> <p style='text-align:right;'> <b> for " . $firmDetails["firm_name"] . " </b> </p><p style='text-align: right;margin-top: 48px;'> Authorised Signatory </p></td></tr></table><p style='width: 100%;text-align: center;margin-top: 12px;'>This is Computer Generated Invoice</p></body></html>";

    return $finalString;
}


function getTableDetails($tableName, $primaryIdColumnName, $primaryId, $con)
{
    $details =  new \stdClass();
    $query = "select * from " . $tableName . " where " . $primaryIdColumnName . " = " . $primaryId;
    $result = $con->query($query);
    $countOfRows = $result->num_rows;

    if ($countOfRows != 0) {
        if ($tableName == "invoice_products") {
            $rowArray = array();
            while ($row = $result->fetch_assoc()) // use fetch_assoc here
            {
                $rowArray[] = $row; // assign each value to array
            }
            // $row = $result->fetch_all(MYSQLI_ASSOC);
            $details = $rowArray;
        } else {
            $row = $result->fetch_assoc();
            $details = $row;
        }
    }
    return $details;
}

function formatDateStringInvoice($date_string)
{
    $date = date_create($date_string);
    return date_format($date, "d-m-Y");
}
function getDateYear($date_string)
{
    $date = date_create($date_string);
    return date_format($date, "Y");
}


function deleteFiles($path)
{
    $files = glob($path); // get all file names
    foreach ($files as $file) { // iterate files
        if (is_file($file)) {
            unlink($file); // delete file
        }
    }
}



function getPanFromGST($gstNo)
{
    return substr($gstNo, 2, strlen($gstNo) - 5);
}


function getMultiLineText($inputText)
{
    $result = "";
    $lines = explode('.', $inputText);
    $result = "<ul>";
    for ($i = 0; $i < count($lines); $i++) {
        $result .= "<li>" . $lines[$i] . "</li>";
    }
    $result .= "</ul>";
    return $result;
}
