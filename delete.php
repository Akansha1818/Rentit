<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rentit";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $userId = $conn->real_escape_string($_POST['user_id']);
    $userName = $conn->real_escape_string($_POST['username']);

    // Delete from users table
    $deleteUserSql = "DELETE FROM users WHERE user_id = '$userId'";
    if ($conn->query($deleteUserSql) === TRUE) {
        // Optionally drop the user-specific table if it exists
        $dropTableSql = "DROP TABLE IF EXISTS `$userName`";
        $conn->query($dropTableSql);
        
        // Redirect back to the main page after deletion
        header("Location: user.php");
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
