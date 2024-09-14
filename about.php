<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent It</title>
    <!-- Custom CSS link -->
    <link rel="stylesheet" href="./assets/css/style.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="about" style="margin-top: 5%; display: flex; align-items: center; justify-content: center; flex-direction: column; padding: 2rem; gap: 2rem">
        <?php
        // Enable error reporting
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

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
        // Get the car ID from the URL
        $item_id = isset($_GET['item_id']) ? $_GET['item_id'] : null;

        // Ensure item_id is numeric
        if ($item_id && is_numeric($item_id)) {

            $sql = "SELECT * FROM rent WHERE id = $item_id";
            $result = $conn->query($sql);

            // Check if rows are returned
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<h3 style = 'font-size: 2rem;'>" . htmlspecialchars($row["category"]) . "</h3>";
                    ?>
                    <div style="display: flex;">
                        <?php echo "<p>" . htmlspecialchars($row["details"]) . "</p>";
                        echo "<img src='". htmlspecialchars($row["image_path"])."'width=20%>";
                        ?>
                    </div>
                    <?php
                    echo "<h3>" ."Rent: ". htmlspecialchars($row["price"]) . " /day </h3>";
                }
            } else {
                echo "No car found.";
            }
        } else {
            echo "Invalid car ID.";
        }
        // Close the connection
        $conn->close();
        ?>
        <div style="border: 1px solid grey; width: 60%; padding: 2rem;">
            <h2 style="text-align: center;">Request To Rent</h2>
            <form action="user.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required style="border: 2px solid grey; padding: 2px 10px; border-radius: 1rem;"><br><br>
                <label for="pname">Product Name:</label>
                <input type="text" id="pname" name="pname" required style="border: 2px solid grey; padding: 2px 10px; border-radius: 1rem;"><br><br>

                <label for="phoneno">Phone Number:</label>
                <input type="text" id="phoneno" name="phoneno" required style="border: 2px solid grey; padding: 2px 10px; border-radius: 1rem;"><br><br>
                
                <label for="rental">Date of Rental:</label>
                <input type="date" id="rental" name="rental" required style="border: 2px solid grey; padding: 2px 10px; border-radius: 1rem;"><br><br>
                
                <label for="return">Date of Return:</label>
                <input type="date" id="return" name="return" required style="border: 2px solid grey; padding: 2px 10px; border-radius: 1rem;"><br><br>
                
                <label for="delivery">Select Delivery Option:</label>
                <select id="delivery" name="delivery" style="padding: 0.5rem 1rem;">
                    <option value="Home Delivery">Home Delivery</option>
                    <option value="Personal Visit">Personal Visit</option>
                </select><br><br>
                <button type="submit" value="Submit" style="background-color: grey; color: white; padding: 2px 10px; border-radius: 1rem; width: fit-content;">Submit</button>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
