<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../objects/rates.php';

$database = new Database();
$db = $database->getConnection();
$rates = new Rate($db);
// set ID property of record to read
$rates->id = isset($_GET['id']) ? $_GET['id'] : die();
// read the details of rate to be edited
$rates->readOne();
if ($rates->rate != null) {
    // Retrieve rate details from the object
    $id = $rates->id;
    $rate = $rates->rate;
    $updated_by = $rates->updated_by;
    // Create an array with these values
    $rates_arr = array(
        "id" => $id,
        "rate" => $rate,
        "updated_by" => $updated_by
    );
    http_response_code(200);
    echo json_encode($rates_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "rate does not exist."));
}
?>
