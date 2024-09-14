<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booked</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
<?php
    include 'navbar.php';
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

    // Handle delete request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
        $userId = $conn->real_escape_string($_POST['user_id']);
        $userName = $conn->real_escape_string($_POST['username']);

        // Delete from users table
        $deleteUserSql = "DELETE FROM users WHERE user_id = '$userId'";
        if ($conn->query($deleteUserSql) === TRUE) {
            // Check if the username still exists in the users table
            $checkUserExistsSql = "SELECT * FROM users WHERE username = '$userName'";
            $result = $conn->query($checkUserExistsSql);

            // Only drop the table if there are no more rows with that username
            if ($result->num_rows == 0) {
                $dropTableSql = "DROP TABLE IF EXISTS `$userName`";
                $conn->query($dropTableSql);
            }
        } else {
            echo "Error deleting user: " . $conn->error;
        }
    }

    // Handle insert logic
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'], $_POST['pname'], $_POST['phoneno'], $_POST['rental'], $_POST['return'], $_POST['delivery'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $pname = $conn->real_escape_string($_POST['pname']);
        $phoneno = $conn->real_escape_string($_POST['phoneno']);
        $rental = $conn->real_escape_string($_POST['rental']);
        $return = $conn->real_escape_string($_POST['return']);
        $delivery = $conn->real_escape_string($_POST['delivery']);

        // Insert into users table
        $sql = "INSERT INTO users (username, product) VALUES ('$name', '$pname')";
        if($conn->query($sql) !== TRUE){
            echo "Error inserting into users: " . $conn->error;
        }

        // Check if user-specific table exists
        $checkTableSQL = "SHOW TABLES LIKE '$name'";
        $result = $conn->query($checkTableSQL);

        if ($result->num_rows == 0) {
            // Create table if it does not exist
            $createTableSQL = "CREATE TABLE `$name` (
                ProductName VARCHAR(50) NOT NULL,
                Phoneno VARCHAR(20) NOT NULL,
                RentalDate DATE NOT NULL, 
                ReturnDate DATE NOT NULL,
                Delivery VARCHAR(20) NOT NULL
            )";

            if ($conn->query($createTableSQL) !== TRUE) {
                echo "Error creating table: " . $conn->error;
            }
        }

        // Insert into user-specific table
        $sql = "INSERT INTO `$name` (ProductName, Phoneno, RentalDate, ReturnDate, Delivery) VALUES ('$pname', '$phoneno', '$rental', '$return', '$delivery')";
        if($conn->query($sql) !== TRUE){
            echo "Error inserting into $name table: " . $conn->error;
        }
    }

    // Fetch and display data from users table
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    echo '<div style="margin-top: 7%;">
        <h2 style="text-align: center; padding: 2rem;">Our Bookings</h2>
        <table style="width: 70%; border-collapse: collapse; margin: auto;">
            <thead>
                <tr>
                    <th style="border: 1px solid black; padding: 8px; text-align: left;">Name</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: left;">Product Name</th>
                    <th style="border: 1px solid black; padding: 8px; text-align: left;">Delete</th>
                </tr>
            </thead>
            <tbody>';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo '<tr id="row_'.$row['user_id'].'">
                <td style="border: 1px solid black; padding: 8px; text-align: left;">' . $row['username'] . '</td>
                <td style="border: 1px solid black; padding: 8px; text-align: left;">' . $row['product'] . '</td>
                <td style="border: 1px solid black; padding: 8px; text-align: left;">
                    <form method="POST" action="">
                        <input type="hidden" name="user_id" value="' . $row['user_id'] . '">
                        <input type="hidden" name="username" value="' . $row['username'] . '">
                        <button type="submit" name="delete" style="background-color: green; border-radius: 1rem; color: white; padding: 6px 10px; font-size: 0.9rem;">Delete</button>
                    </form>
                </td>
            </tr>';
        }
    }
    echo '</tbody>
        </table>
    </div>';

    // Close the connection
    $conn->close();
?>
</body>
</html>
