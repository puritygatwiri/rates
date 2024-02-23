<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
include_once '../config/database.php';
include_once '../objects/rates.php';
  
$database = new Database();
$db = $database->getConnection(); 
$rate = new Rate($db); 
// query rate
$stmt = $rate->read();
$num = $stmt->rowCount(); 
// check if more than 0 record found
if($num>0){
    // rates array
    $rates_arr=array();
    $rates_arr["records"]=array();
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        extract($row);
        $rate_item=array(
            "id" => $id,
            "rate" => $rate,
            "updated_by" => $updated_by
        );
        array_push($rates_arr["records"], $rate_item);
    }
    http_response_code(200); 
    echo json_encode($rates_arr);
}
else{
    http_response_code(404);
    echo json_encode(
        array("message" => "No rates found.")
    );
}