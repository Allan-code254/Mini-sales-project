<?php
session_start();

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

    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to find the user
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "Login successful! Welcome, " . $_SESSION['username'];
            // Redirect to the home page (or any other page)
            header("Location: home.php");
            exit;
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that email address!";
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
    <title>Login - ReJo Sales</title>
    <style>
        /* Your login form styling goes here */
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Welcome Back!</h2>
        <p>Login to access your ReJo Sales account.</p>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <a href="signup.php" class="signup-link">Don't have an account? Signup</a>
    </div>
</body>
</html>
