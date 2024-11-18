<?php
// Database connection
$host = 'localhost';
$db = 'sales_system';
$user = 'root';  // Use your actual database username
$pass = '';  // Use your actual database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check_query);
    
    if ($result->num_rows > 0) {
        $error_message = "Email is already registered!";
    } else {
        // Insert the new user into the database
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        
        if ($conn->query($query) === TRUE) {
            $success_message = "Registration successful! You can now <a href='login.php'>login</a>.";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ReJo Sales</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="shop.html">Shop</a></li>
                <li><a href="cart.html">Cart</a></li>
                <li><a href="login.html">Login</a></li>
                <li><a href="signup.html">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <div class="signup-container">
        <h2>Create a New Account</h2>

        <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
        <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>

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
            <button type="submit" class="signup-btn">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
