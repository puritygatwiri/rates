<?php
class Rate{
    // db connection and table name
    private $conn;
    private $table_name = "rate";
    // object properties
    public $id;
    public $rate;
    public $updated_by;
    public $update_date;
    //constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // read rates
function read(){
    $query = "SELECT * FROM rate";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // execute query
    $stmt->execute();
    return $stmt;
}
// create rate
function create(){
    $query = "INSERT INTO " . $this->table_name . "
    SET rate=:rate, updated_by=:updated_by, update_date=:update_date";
    // prepare query
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->rate=htmlspecialchars(strip_tags($this->rate));
    $this->updated_by=htmlspecialchars(strip_tags($this->updated_by));
    $this->update_date=htmlspecialchars(strip_tags($this->update_date));
    // bind values
    $stmt->bindParam(":rate", $this->rate);
    $stmt->bindParam(":updated_by", $this->updated_by);
    $stmt->bindParam(":update_date", $this->update_date);
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false; 
}
// used when filling up the update rate form
function readOne() {
    $query = "SELECT
                id, rate, updated_by, update_date
            FROM
                " . $this->table_name . "
            WHERE
                id = ?
            LIMIT
                0,1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);
    if ($stmt->execute()) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->rate = $row['rate'];
            $this->updated_by = $row['updated_by'];
            $this->update_date = $row['update_date'];
            return true;
        } else {
            return false;
        }
    } else {
        return false; 
    }
}
// update the rate
function update(){  
    $query = "UPDATE
                " . $this->table_name . "
            SET
                rate = :rate,
                updated_by = :updated_by
            WHERE
                id = :id";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    // sanitize
    $this->rate=htmlspecialchars(strip_tags($this->rate));
    $this->updated_by=htmlspecialchars(strip_tags($this->updated_by));
    $this->update_date=htmlspecialchars(strip_tags($this->update_date));
    $this->id=htmlspecialchars(strip_tags($this->id));
    // bind new values
    $stmt->bindParam(':rate', $this->rate);
    $stmt->bindParam(':updated_by', $this->updated_by);
    $stmt->bindParam(':id', $this->id);
    // execute the query
    if($stmt->execute()){
        return true;
    }
    return false;
}
 // function to update multiple records
    function updateMultiple($dataToUpdate) {
        $query = "UPDATE " . $this->table_name . " SET rate = ?, updated_by = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        // iterate through each data set and execute the update query
        foreach ($dataToUpdate as $data) {
            $stmt->execute([$data->rate, $data->updated_by, $data->id]);
        }
        // check for successful updates
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
// delete the rate
function delete(){
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $this->id=htmlspecialchars(strip_tags($this->id));
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
    // execute query
    if($stmt->execute()){
        return true;
    }
    return false;
}
function deleteMultiple($idsToDelete) {
    $query = "DELETE FROM " . $this->table_name . " WHERE id IN (";
    // create a string with placeholders for each ID
    $placeholders = str_repeat('?,', count($idsToDelete) - 1) . '?';
    // concatenate the placeholders into the query
    $query .= $placeholders . ")";
    // prepare query
    $stmt = $this->conn->prepare($query);
    // execute query with the array of IDs
    if ($stmt->execute($idsToDelete)) {
        return true;
    }
    return false;
}
}
?>