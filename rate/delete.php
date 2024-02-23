<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/rates.php';
$database = new Database();
$db = $database->getConnection();
$rate = new Rate($db);
// get rate id from the input data
$data = json_decode(file_get_contents("php://input"));
// Check if 'id' is set in the input data
if (!empty($data->id)) {
    $rate->id = $data->id;
    // delete the rate
    if ($rate->delete()) {
        http_response_code(200);
        echo json_encode(array("message" => "rate was deleted."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete rate."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Invalid data. 'id' is missing."));
}
?>
