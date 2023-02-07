<?php
function sendEmail($email_var, $email_subject, $message_var)
{

    $to =  $email_var;
    $from = "no-reply@yashhenterprises.co.in";
    $subject = $email_subject;
    $message = $message_var;

    $headers = "From: $from";
    $ok = @mail($to, $subject, $message, $headers, "-f " . $from);
    return $ok;
}
