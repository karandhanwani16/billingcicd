<?php


include("../../../../services/env.php");

$dbHost = $HOST;
$dbUsername = $USER;
$dbPassword = $PASSWORD;
$dbName = $DATABASE;
$tables = '*';

$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

//get all of the tables
if ($tables == '*') {
    $tables = array();
    $result = $db->query("SHOW TABLES");
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
} else {
    $tables = is_array($tables) ? $tables : explode(',', $tables);
}

$return = "";

foreach ($tables as $table) {
    $result = $db->query("SELECT * FROM $table");
    $numColumns = $result->field_count;

    // $return .= "DROP TABLE $table;";

    $result2 = $db->query("SHOW CREATE TABLE $table");
    $row2 = $result2->fetch_row();

    $return .= "\n\n" . $row2[1] . ";\n\n";

    for ($i = 0; $i < $numColumns; $i++) {
        while ($row = $result->fetch_row()) {
            $return .= "INSERT INTO $table VALUES(";
            for ($j = 0; $j < $numColumns; $j++) {
                $row[$j] = addslashes($row[$j]);
                // $row[$j] = preg_replace("\n", "\\n", $row[$j]);
                if (isset($row[$j])) {
                    $return .= '"' . $row[$j] . '"';
                } else {
                    $return .= '""';
                }
                if ($j < ($numColumns - 1)) {
                    $return .= ',';
                }
            }
            $return .= ");\n";
        }
    }

    $return .= "\n\n\n";
}

$finalObject =  new \stdClass();

//save db file in same folder
$fileName = "backupFiles/" . $DATABASE . "_" . date("d_M_Y") . ".sql";
// $fileName = "backupFiles/" . $DATABASE . "_" . time() . ".sql";

deleteRestFiles("../" . $fileName);

$handle = fopen("../" . $fileName, 'w+');

if (fwrite($handle, $return) === false) {
    $finalObject->status = "error";
    $finalObject->message = "Error #1001";
}

chmod("../" . $fileName, 0777);
fclose($handle);

Header('Content-type: application/octet-stream');
Header('Content-Disposition: attachment; filename=' . $DATABASE . "_" . date("d_M_Y") . ".sql");


// Recipient 
$to = $backupEmail;

// Sender 
$from = $billingMainEmail;
$fromName = $companyName;

// Email subject 
$subject = 'Backup of yash Consumer on ' . date("M d,Y");

// Attachment file 
$file = "../" . $fileName;

// Email body content 
$htmlContent = "<h1>Yash Consumer</h1> <p>This email is sent from yash Consumer with attachment of backup for date: " . date("M d,Y") . "</p> ";

// Header for sender info 
$headers = "From: $fromName" . " <" . $from . ">";

// Boundary  
$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

// Headers for attachment  
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

// Multipart boundary  
$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";

// Preparing attachment 
if (!empty($file) > 0) {
    if (is_file($file)) {
        $message .= "--{$mime_boundary}\n";
        $fp =    @fopen($file, "rb");
        $data =  @fread($fp, filesize($file));

        @fclose($fp);
        $data = chunk_split(base64_encode($data));
        $message .= "Content-Type: application/octet-stream; name=\"" . basename($file) . "\"\n" .
            "Content-Description: " . basename($file) . "\n" .
            "Content-Disposition: attachment;\n" . " filename=\"" . basename($file) . "\"; size=" . filesize($file) . ";\n" .
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    }
}
$message .= "--{$mime_boundary}--";
$returnpath = "-f" . $from;

// Send email 
$mail = @mail($to, $subject, $message, $headers, $returnpath);

if ($mail) {
    $finalObject->status = "success";
    $finalObject->message = "Backup sent to the Email on : " . $to;
    $finalObject->filename = $fileName;
} else {
    $finalObject->status = "error";
    $finalObject->message = "Error in sending email";
}

$response = json_encode($finalObject);
echo $response;


function deleteRestFiles($filename)
{
    $folder_path = "../backupFiles/";
    $files = glob($folder_path . '/*');
    // Deleting all the files in the list
    foreach ($files as $file) {
        if ($file != $filename) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
