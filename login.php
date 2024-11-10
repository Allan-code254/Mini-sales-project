<?php
// Include database connection file
require 'db.php';

// Start session for storing user data after login
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php'); // Redirect to dashboard if already logged in
    exit();
}

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate user credentials
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to the dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = 'Incorrect password!';
        }
    } else {
        $error_message = 'No user found with that username!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ReJo Sales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #3b82f6;
            padding: 1rem;
            color: white;
            text-align: center;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            padding: 2rem;
        }

        .login-box {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .input-field {
            width: 100%;
            padding: 1rem;
            margin: 0.5rem 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .input-field:focus {
            border-color: #3b82f6;
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 1rem;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2563eb;
        }

        .error-message {
            color: red;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        footer {
            background-color: #3b82f6;
            color: white;
            padding: 1rem;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <header>
        <h1>ReJo Sales - Login</h1>
    </header>

    <!-- Login Form Section -->
    <div class="login-container">
        <div class="login-box">
            <h2>Login to Your Account</h2>

            <!-- Error message display -->
            <?php if (isset($error_message)): ?>
                <div class="error-message"><?= $error_message ?></div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="login.php" method="POST">
                <input type="text" name="username" class="input-field" placeholder="Username" required>
                <input type="password" name="password" class="input-field" placeholder="Password" required>
                <button type="submit" class="btn">Login</button>
            </form>
            <p style="text-align:center; margin-top: 1rem;">
                Don't have an account? <a href="signup.php" style="color:#3b82f6;">Sign Up</a>
            </p>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 ReJo Sales. All Rights Reserved.</p>
    </footer>

</body>

</html>
