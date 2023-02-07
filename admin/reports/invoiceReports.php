<?php

include("../services/urlValidation.php");
include("../services/config.php");
include("../services/helperFunctions.php");
// session_start();
// checkUrlValidation("admin", "login.php");
// $user_id = $_SESSION["user_id"];

function loadFirm($con)
{
    $options = "";
    $query = "select * from firm";
    $result = $con->query($query);
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row['firm_id'] . "' >" . $row['firm_name'] . "</option>";
    }
    return $options;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
    <link rel="stylesheet" href="../style/viewtable.css">
</head>

<body style="padding: 16px 24px;">

    <div class="alert--cont">
    </div>

    <h1>Invoice Reports</h1>
    <div class="spacer"></div>
    <!-- alert cont end -->

    <form action="#">
        <div class="inp-row adj-row row-4">
            <div class="inp-group monthyear-inp">
                <div class="half-inp-group">
                    <div class="inp-label">From Month</div>
                    <select id="ddlfrommonth" class="month-ddl ddl required" data-id="ddlfrommonth">
                    </select>
                    <div class="error-text" data-id="ddlfrommonth">Cannot leave this field blank</div>
                </div>

                <div class="half-inp-group">
                    <div class="inp-label">From year</div>
                    <select id="ddlfromyear" class="year-ddl ddl required" data-id="ddlfromyear">
                    </select>
                    <div class="error-text" data-id="ddlfromyear">Cannot leave this field blank</div>
                </div>


            </div>
            <!-- inp group end -->
            <div class="inp-group monthyear-inp">
                <div class="half-inp-group">
                    <div class="inp-label">To Month</div>
                    <select id="ddltomonth" class="month-ddl ddl required" data-id="ddltomonth">
                    </select>
                    <div class="error-text" data-id="ddltomonth">Cannot leave this field blank</div>
                </div>

                <div class="half-inp-group">
                    <div class="inp-label">To year</div>
                    <select id="ddltoyear" class="year-ddl ddl required" data-id="ddltoyear">
                    </select>
                    <div class="error-text" data-id="ddltoyear">Cannot leave this field blank</div>
                </div>


            </div>
            <!-- inp group end -->
        </div>
        <!-- input row end -->
        <div class="inp-row adj-row row-4">
            <div class="inp-group">
                <div class="inp-label">Firm</div>
                <select id="ddlfirm" class="ddl required" data-id="ddlfirm">
                    <option value="">Select Firm</option>
                    <?php
                    echo loadFirm($con);
                    ?>
                </select>
                <div class="error-text" data-id="ddlfirm">Cannot leave this field blank</div>
            </div>
            <!-- inp group end -->
        </div>
        <!-- input row end -->
        <div class="btn-row">
            <div class="primary-btn btn f-center location--submit--btn">Download</div>
        </div>
    </form>


    <!-- View Table Start -->
    <table class="view--table">
    </table>
    <!-- View Table End -->

    <script src="../scripts/helperFunctions.js"></script>
    <!-- get Variables start -->
    <script>
        let fromMonthDdl = document.querySelector("#ddlfrommonth");
        let fromYearDdl = document.querySelector("#ddlfromyear");
        let toMonthDdl = document.querySelector("#ddltomonth");
        let toYearDdl = document.querySelector("#ddltoyear");
        let firmDdl = document.querySelector("#ddlfirm");
        let submitBtn = document.querySelector(".location--submit--btn");
        let viewTable = document.querySelector(".view--table");
    </script>
    <!-- get Variables end -->

    <!-- get View Data Start-->
    <script>
        let ddlists = document.querySelectorAll("select");

        ddlists.forEach(ddl => {
            ddl.addEventListener("change", e => {
                loadViewData();
            });
        });

        function loadViewData() {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    loadDataIntoTable(result.arr);
                }
            };
            viewTable.innerHTML = "Loading...";
            xmlhttp.open("POST", `services/getInvoiceMainReport.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(`frommonth=${fromMonthDdl.value}&fromyear=${fromYearDdl.value}&tomonth=${toMonthDdl.value}&toyear=${toYearDdl.value}&firm=${firmDdl.value}&view=true`);

        }

        function loadDataIntoTable(resultArray) {
            let finalResultString = "";
            let firstRow = 0;
            let lastRow = resultArray.length - 1;
            let i = 0;
            resultArray.forEach(resultArrayRow => {
                if (i != lastRow) {
                    finalResultString += "<tr>";
                    let j = 0;
                    resultArrayRow.forEach(resultArrayCell => {
                        finalResultString += i == 0 ? "<th>" : "<td>";
                        finalResultString += j == 1 ? resultArrayCell : formatNumberToIndian(resultArrayCell);
                        finalResultString += i == 0 ? "</th>" : "</td>";
                        j++;
                    });

                    finalResultString += "</tr>";
                }
                i++;
            });
            // concatenating last row
            finalResultString += `<tfoot><tr><td></td><td></td><td>Total</td><td>${formatNumberToIndian(resultArray[lastRow][3])}</td><td>${formatNumberToIndian(resultArray[lastRow][4])}</td><td>${formatNumberToIndian(resultArray[lastRow][5])}</td></tr></tfoot>`;
            viewTable.innerHTML = finalResultString;
        }


        function formatNumberToIndian(num) {
            return num.toLocaleString('en-IN', {
                maximumFractionDigits: 2,
                style: 'currency',
                currency: 'INR'
            });
        }
    </script>

    <!-- get View Data End-->

    <!-- submitting data -->
    <script>
        function getMonthWord(month) {
            let word = "";
            switch (month) {
                case '1':
                    word = "Jan";
                    break;
                case '2':
                    word = "Feb";
                    break;
                case '3':
                    word = "Mar";
                    break;
                case '4':
                    word = "Apr";
                    break;
                case '5':
                    word = "May";
                    break;
                case '6':
                    word = "June";
                    break;
                case '7':
                    word = "July";
                    break;
                case '8':
                    word = "Aug";
                    break;
                case '9':
                    word = "Sept";
                    break;
                case '10':
                    word = "Oct";
                    break;
                case '11':
                    word = "Nov";
                    break;
                case '12':
                    word = "Dec";
                    break;
                default:
                    break;
            }
            return word;
        }
        submitBtn.addEventListener("click", e => {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var result = JSON.parse(this.responseText);
                    filename = getMonthWord(fromMonthDdl.value) + "_" + fromYearDdl.value + "_" + getMonthWord(toMonthDdl.value) + "_" + toYearDdl.value + "_" + result.id + "_invoice_report";
                    let pdfUrl = `../../assets/files/${filename}.xlsx`;
                    var link = document.createElement('a');
                    link.href = pdfUrl;
                    link.addEventListener("click", e => {
                        e.preventDefault();
                    });
                    link.dispatchEvent(new MouseEvent('click'));
                    removeLoadingStateWithText(submitBtn, "Download");
                    showAlert(result.message, result.status);
                }
            };
            addLoadingStateWithText(submitBtn, "Downloading!!!");
            xmlhttp.open("POST", `services/getInvoiceMainReport.php`, true);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(`frommonth=${fromMonthDdl.value}&fromyear=${fromYearDdl.value}&tomonth=${toMonthDdl.value}&toyear=${toYearDdl.value}&firm=${firmDdl.value}&view=`);
        });
    </script>
    <!-- load products data -->
    <script>
        function loadMonthDataAll() {
            let monthDdls = document.querySelectorAll(".month-ddl");
            let currentMonth = d.getMonth();
            monthDdls.forEach(monthDdl => {
                monthDdl.innerHTML = "<option value=''>Select Month</option>";
                for (let i = 0; i < months.length; i++) {
                    if (i == currentMonth) {
                        monthDdl.innerHTML += `<option selected value="${i+1}">${months[i]}</option>`;
                    } else {
                        monthDdl.innerHTML += `<option value="${i+1}">${months[i]}</option>`;
                    }
                }

            });
        }

        function loadYearDataAll() {
            let yearDdls = document.querySelectorAll(".year-ddl");
            let currentYear = d.getFullYear();
            let bandwidth = 3;
            let startYear = currentYear - bandwidth;
            let endYear = currentYear + bandwidth;

            yearDdls.forEach(yearDdl => {
                yearDdl.innerHTML = "<option value=''>Select Year</option>";
                for (let i = startYear; i <= endYear; i++) {
                    if (i == currentYear) {
                        yearDdl.innerHTML += `<option selected value="${i}">${i}</option>`;
                    } else {
                        yearDdl.innerHTML += `<option value="${i}">${i}</option>`;
                    }
                }
            });
        }
        window.onload = function exampleFunction() {
            // getNewData();
            loadMonthDataAll();
            loadYearDataAll();
            loadViewData();

        }
    </script>
</body>

</html>