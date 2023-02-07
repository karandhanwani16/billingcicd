<?php
include("../services/config.php");
function getFirmData($con)
{
    $finalResult = "";
    try {
        $query = "select * from firm";
        $result = $con->query($query);
        $finalResult .= "{";
        $totalCount = $result->num_rows;
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            // {"true":"true","false":"false"}
            $finalResult .= '"';
            $finalResult .= $row['firm_id'];
            $finalResult .= '"';
            $finalResult .= ":";
            $finalResult .= '"';
            $finalResult .= $row['firm_name'];
            $finalResult .= '"';
            if ($count != $totalCount) {
                $finalResult .= ",";
            }
            $count++;
        }
        $finalResult .= "}";
    } catch (exception $e) {
    }
    echo $finalResult;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/assets.css">
    <link rel="stylesheet" href="../style/forms.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://markcell.github.io/jquery-tabledit/assets/js/tabledit.min.js"></script>


</head>

<body>
    <div class="alert--cont">
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Buyer</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="sample_data" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Firm</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>GST No.</th>
                            <th>State</th>
                            <th>State Code</th>
                            <th>HSN Code</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>


    <script src="../scripts/helperFunctions.js"></script>
    <script type="text/javascript" language="javascript">
        let firmData = '<?php echo getFirmData($con); ?>';
        $(document).ready(function() {

            var dataTable = $('#sample_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "services/getSupplierData.php",
                    type: "POST"
                }
            });

            $('#sample_data').on('draw.dt', function() {
                $('#sample_data').Tabledit({
                    url: 'services/supplier_action.php',
                    dataType: 'json',
                    columns: {
                        identifier: [0, 'supplier_id'],
                        editable: [
                            [1, 'firm_id', firmData],
                            [2, 'supplier_name'],
                            [3, 'supplier_address'],
                            [4, 'supplier_gst_no'],
                            [5, 'supplier_state'],
                            [6, 'supplier_state_code'],
                            [7, 'supplier_hsn_code']
                        ]
                    },
                    restoreButton: false,
                    onSuccess: function(data, textStatus, jqXHR) {

                        if (data.error) {
                            showAlert(data.error, "error");
                        }
                        if (data.action == 'delete') {
                            $('#' + data.id).remove();
                            $('#sample_data').DataTable().ajax.reload();
                        }
                    }
                });
            });



        });
    </script>


</body>

</html>