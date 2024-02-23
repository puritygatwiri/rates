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
$rates = new Rate($db);
// get id of rate to be edited
$data = json_decode(file_get_contents("php://input"));
// Log the received data for debugging
error_log('Received data:');
error_log(print_r($data, true));
if ($data) {
    if (isset($data->id)) {
        $rates->id = $data->id;
        // set rate property values
        $id = $rates->id;
        $rates->rate = $data->rate;
        $rates->updated_by = $data->updated_by;
        // update the rate
        if ($rates->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "rate was updated."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update rate."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Missing rate ID."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "No data received."));
}
?>
