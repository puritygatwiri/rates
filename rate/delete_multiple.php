<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/rates.php';

$database = new Database();
$db = $database->getConnection();
$rate = new Rate($db);
// get rate IDs from the input data
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->ids)) {
    $idsToDelete = $data->ids;
    // delete multiple rates
    if ($rate->deleteMultiple($idsToDelete)) {
        http_response_code(200);
        echo json_encode(array("message" => "Rates were deleted."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete rates."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Invalid data. 'ids' are missing."));
}