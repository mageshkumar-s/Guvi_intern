<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

require '../vendor/autoload.php';

try {
    // Check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Connect to MongoDB
        $mongoClient = new MongoDB\Client("mongodb+srv://admin:admin123@cluster0.9fwt8v8.mongodb.net/profile_details");

        // Select database and collection
        $mongoDB = $mongoClient->profile_details; 
        $mongoCollection = $mongoDB->users;

        // Retrieve form data
        $age = $_POST['age'];
        $dob = $_POST['dob'];
        $contact = $_POST['contact'];

        // Insert data into the collection
        $insertResult = $mongoCollection->insertOne([
            'age' => $age,
            'dob' => $dob, 
            'contact' => $contact
        ]);

        if ($insertResult->getInsertedCount() > 0) {
            echo json_encode(array("success" => true, "message" => "Profile inserted successfully!"));
        } else {
            echo json_encode(array("success" => false, "message" => "None of the files updated"));
        }
    } else {
        // Invalid request method
        echo json_encode(array("success" => false, "message" => "Invalid request"));
    }
} catch (Exception $e) {
    // Output the exception message as JSON
    echo json_encode(array("success" => false, "message" => $e->getMessage()));
}
?>
