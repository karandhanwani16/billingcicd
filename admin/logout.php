<?php

session_start();
session_unset();
session_destroy();
unset($_SESSION["user_id"]);
unset($_SESSION["user_type"]);
unset($_SESSION["logged_in"]);
header("Location: ../login.php");
exit;
