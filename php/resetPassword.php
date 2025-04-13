<?php
// resetPassword.php

// Database connection (update these values)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_calendar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Simulating sending a reset link
        // In real life, you'd generate a token, store it, and send an email.
        echo "
        <script>
            alert('Password reset link has been sent to your email!');
            window.location.href = 'login.html';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('No account found with that email!');
            window.location.href = 'forgotPassword.html';
        </script>
        ";
    }

    $stmt->close();
}

$conn->close();
?>
