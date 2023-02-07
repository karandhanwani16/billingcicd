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
    <link rel="stylesheet" href="../style/tables.css">

</head>

<body>
    <div class="alert--cont">
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Firms</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="sample_data" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Select</th>
                            <th>Name</th>
                            <th>GST</th>
                            <th>Address</th>
                            <th>State</th>
                            <th>State Code</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>


    <script src="../scripts/helperFunctions.js"></script>
    <script type="text/javascript" language="javascript">
        $(document).ready(function() {

            var dataTable = $('#sample_data').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "services/getFirmData.php",
                    type: "POST"
                }
            });
            $('#sample_data').on('draw.dt', function() {
                $('#sample_data').Tabledit({
                    url: 'services/firm_action.php',
                    dataType: 'json',
                    columns: {
                        identifier: [0, 'firm_id'],
                        editable: []
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