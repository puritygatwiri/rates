<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/polepole/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
include_once '../config/database.php';
  
include_once '../objects/rates.php'; 
$database = new Database();
$db = $database->getConnection(); 
$rate = new Rate($db); 
// get posted data
$data = json_decode(file_get_contents("php://input"));
// make sure data is not empty
if(
    !empty($data->rate) &&
    !empty($data->updated_by) 
){
    // set rate property values
    $rate->rate = $data->rate;
    $rate->updated_by = $data->updated_by;
    $rate->update_date = $data->update_date;
    // create the rate
    if($rate->create()){
        http_response_code(201);
        echo json_encode(array("message" => "successful"));
    }
    else{
        http_response_code(503);
        echo json_encode(array("message" => "failed."));
    }
}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Unable  Data is incomplete."));
}
?>