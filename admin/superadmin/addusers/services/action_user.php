<?php

include "../../../services/config.php";
include "../../../services/helperFunctions.php";


try {
    if ($_POST['action'] == 'edit') {
        $email = addslashes($_POST["user_email"]);
        $fname = addslashes($_POST["user_first_name"]);
        $lname = addslashes($_POST["user_last_name"]);
        $type = addslashes($_POST["user_type"]);
        $query = "update users set user_type='" . $type . "',user_email='" . $email . "',user_first_name='" . $fname . "',user_last_name='" . $lname . "' where user_id =" . $_POST['user_id'];
        mysqli_query($con, $query);
        
    }
    if ($_POST['action'] == 'delete') {
        $query = "delete from users where user_id = " . $_POST['user_id'];
        mysqli_query($con, $query);
    }
} catch (exception $e) {
    $_POST["error"] = "Error #1001";
}
echo json_encode($_POST);
