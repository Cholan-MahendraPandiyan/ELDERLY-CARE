<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'ooadproject');
    if ($conn->connect_error) {
        echo "$conn->connect_error";
        die("Connection Failed : " . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("INSERT INTO coordinates (latitude, longitude) VALUES (?, ?)");
        if (!$stmt) {
            echo "Error in statement preparation: " . $conn->error;
        } else {
            $stmt->bind_param("dd", $latitude, $longitude);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            // Return a response to be displayed on the page
            echo "Coordinates stored successfully!";
        }
    }
} else {
    // Handle invalid requests
    http_response_code(400);
    echo 'Invalid request';
}
?>
