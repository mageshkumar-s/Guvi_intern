<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Database connection
    $conn = require_once("db.php");

    // Receive data from the signup form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $checkUserStmt = $conn->prepare("SELECT email FROM registration_details WHERE email = ?");
    $checkUserStmt->bind_param("s", $username);
    $checkUserStmt->execute();
    $checkUserStmt->store_result();

    if ($checkUserStmt->num_rows > 0) {
        $response = array("status" => "error", "message" => "❌ Email already registered");
        echo json_encode($response);
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare statement to insert data into the database
        $stmt = $conn->prepare("INSERT INTO registration_details (username ,email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            // Signup successful
            $response = array('status' => 'success');
            echo json_encode($response);
        } else {
            // Signup failed
            $response = array('status' => 'error');
            echo json_encode($response);
        }
        $stmt->close();
    }
    
    $checkUserStmt->close();
    $conn->close();
} else {
    // Invalid request
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request'));
}
?>
