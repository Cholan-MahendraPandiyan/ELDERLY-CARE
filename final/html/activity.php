<?php
    $activity = $_POST['activity'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'ooadproject');
    if ($conn->connect_error) {
        echo "$conn->connect_error";
        die("Connection Failed : " . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("INSERT INTO activity(activity) VALUES (?)");
        if (!$stmt) {
            echo "Error in statement preparation: " . $conn->error;
        } else {
            $stmt->bind_param("s", $activity);
            $stmt->execute();
            $stmt_result = $stmt->get_result();
            $stmt->close();
            $conn->close();

            // Return a response to be displayed on the page
            //echo "Activity logged successfully!";
            header("Location: activity.html");
            exit();
        }
    }
?>
