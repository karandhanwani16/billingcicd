<?php
include "../../services/config.php";
include "../../services/helperFunctions.php";


$query = "select * from firm";
$result = $con->query($query);
$number_filter_row = $result->num_rows;

$data = array();
if($number_filter_row > 0){
    while ($row = $result->fetch_assoc()) {
        
        $sub_array = array();
        $sub_array[]=$row["firm_id"];
        $sub_array[]=$row["firm_name"];
        
        $data[] = $sub_array;
    }    
}
echo json_encode($data);

?>