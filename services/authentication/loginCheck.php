<?php

include "../config.php";
include "../env.php";
include "../utils/generalFunctions.php";

$finalObject =  new \stdClass();
$email = $_POST["email"];
$password = $_POST["password"];

$currentTimeStamp = getCurrentTimestamp();
if ($email != "" && $password != "") {
    try {
        if (authenticate($email, $password, $con)) {
            // create session value
            $redirectionObject = createSessionValues($email, $con);
            if ($redirectionObject->status == "success") {
                $finalObject->status = "success";
                $finalObject->link = $redirectionObject->link;
            } else {
                $finalObject->status = "error";
                $finalObject->message = $redirectionObject->message;
            }
        } else {
            $finalObject->status = "error";
            $finalObject->message = "Enter valid creditenials !!";
        }
    } catch (Exception $e) {
        $finalObject->status = "error";
        $finalObject->message = "Error #1001";
    }
} else {
    $finalObject->status = "error";
    $finalObject->message = "Enter proper creditenials";
}

$response = json_encode($finalObject);
echo $response;

function authenticate($email, $password, $con)
{
    $query = "select user_password from users where user_email='" . $email . "'";
    $result = $con->query($query);
    if (mysqli_num_rows($result) != 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["user_password"];

        $verify = password_verify($password, $hashedPassword);
        if ($verify) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
    mysqli_close($con);
}


function createSessionValues($email, $con)
{
    $resultObject = new \stdClass();

    $sql = "select user_id,user_type from users where user_email='" . $email . "'";
    // $result = mysqli_query($con, $sql);
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $count = $result->num_rows;
    if ($count != 0) {
        session_start();
        $_SESSION["user_id"] = $row["user_id"];
        $_SESSION["logged_in"] = "true";
        $_SESSION["user_type"] = $row["user_type"];
        $resultObject->status = "success";
        // $resultObject->link = getRedirectionLink($row["user_type"]);
        $resultObject->link = "admin/index.php";
    } else {
        $resultObject->status = "error";
        $resultObject->message = "Error #1002";
    }
    return $resultObject;
}

// function getRedirectionLink($user_type)
// {
//     switch ($user_type) {
//         case "admin":
//             return "admin/index.php";
//             break;
//         case "instructor":
//             return "instructor/index.php";
//             break;
//         default:
//             return "login.php";
//             break;
//     }
// }
