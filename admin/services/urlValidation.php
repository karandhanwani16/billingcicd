<?php


function checkUrlValidation($currentUserType, $redirection_path)
{
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        $user_type = $_SESSION["user_type"];
        if ($user_type != $currentUserType) {
            header('Location: ../unauthorized.php');
            exit;
        }
    } else {
        header('Location: ' . $redirection_path);
        exit;
    }
}


?>