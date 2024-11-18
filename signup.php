<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "localhost"; // Use your database host
    $username = "root";        // Database username (usually root)
    $password = "";            // Database password (usually empty for local dev)
    $dbname = "sales_system";    // Your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the data from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security

    // Insert user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Signup successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - ReJo Sales</title>
    <style>
        /* Your signup form styling goes here */
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Create an Account</h2>
        <p>Join ReJo Sales to explore exclusive deals and offers.</p>
        <form action="signup.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="signup-btn">Signup</button>
        </form>
        <a href="login.php" class="login-link">Already have an account? Login</a>
    </div>
</body>
</html>
