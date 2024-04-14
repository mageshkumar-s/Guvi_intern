<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

require '../mongodb/vendor/autoload.php';
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['username']) && isset($_POST['password'])) {
        
        $conn = require_once("db.php");
        $redis = require_once("redis.php");
        
        
        // Receive data from the registration form
        $usernameEmail = $_POST['username'];
        $password = $_POST['password'];

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare statement to insert user into the database
        $stmt = $conn->prepare("INSERT INTO login_details(email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $usernameEmail, $hashed_password);

        if ($stmt->execute()) {
            // Signup successful
            // Store user data in Redis for 2 hours
            $redis->setex($usernameEmail, 7200, $hashed_password);
            $response = array('status' => 'success');
            echo json_encode($response);
        } else {
            // Signup failed
            http_response_code(500);
            echo json_encode(array('status' => 'error'));
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        // Username or password not provided
        http_response_code(400);
        echo json_encode(array('message' => 'Username or password not provided'));
    }
} else {
    // Invalid request method
    http_response_code(405);
    echo json_encode(array('message' => 'Invalid request method'));
}
?>
