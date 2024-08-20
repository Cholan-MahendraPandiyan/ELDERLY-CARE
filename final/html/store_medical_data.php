<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $conditions = $_POST['conditions'];
    $allergies = $_POST['allergies'];
    $medications = $_POST['medications'];
    $surgeries = $_POST['surgeries'];
    $comments = $_POST['comments'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'ooadproject');
    if ($conn->connect_error) {
        die("Connection Failed : " . $conn->connect_error);
    } else {
        // Prepare and execute the SQL query to insert data into the database
        $stmt = $conn->prepare("INSERT INTO medical_history (name, dob, conditions, allergies, medications, surgeries, comments) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $dob, $conditions, $allergies, $medications, $surgeries, $comments);
        
        // Execute the statement
        if ($stmt->execute()) {
            echo "Medical data stored successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
} else {
    // Handle invalid requests
    http_response_code(400);
    echo 'Invalid request';
}
?>
