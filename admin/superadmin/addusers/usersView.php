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
        <div class="panel-heading">Users</div>
        <div class="panel-body">
            <div class="table-responsive">
                <table id="sample_data" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Type</th>
                            <th>Last Login</th>
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
                    url: "services/getUserData.php",
                    type: "POST"
                }
            });
            $('#sample_data').on('draw.dt', function() {
                $('#sample_data').Tabledit({
                    url: 'services/action_user.php',
                    dataType: 'json',
                    columns: {
                        identifier: [0, 'user_id'],
                        editable: [
                            [1, 'user_email'],
                            [2, 'user_first_name'],
                            [3, 'user_last_name'],
                            [4, 'user_type', '{"super_admin": "Super Admin","salescord": "Billing Executive"}']
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