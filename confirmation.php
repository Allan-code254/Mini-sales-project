<?php 
session_start();

// Payment status
$payment_status = isset($_SESSION['payment_status']) ? $_SESSION['payment_status'] : 'failed';
$payment_total = isset($_SESSION['payment_total']) ? $_SESSION['payment_total'] : 0;

// Clear session payment data
unset($_SESSION['payment_status']);
unset($_SESSION['payment_total']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link rel="stylesheet" href="style.css">
    <meta http-equiv="refresh" content="3;url=shop.php"> <!-- Redirects to shop.php after 3 seconds -->
</head>
<body>
    <section id="header">
        <a href="#"><img src="img/logo.png" class="logo" alt=""></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="transactions.php">Transactions</a></li>
                <li><a href="signup.php" class="auth-link">Signup</a></li>
                <li><a href="login.php" class="auth-link">Login</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </section>

    <div class="container">
        <h1>Payment Confirmation</h1>

        <?php if ($payment_status === 'success'): ?>
            <p style="color: green;">Payment Simulation Successful!</p>
            <p><strong>Amount Paid:</strong> Ksh <?php echo number_format($payment_total, 2); ?></p>
            <p>Thank you for shopping with us!</p>
        <?php else: ?>
            <p style="color: red;">Payment Simulation Failed. Please try again.</p>
        <?php endif; ?>

        <p>You will be redirected to the shop shortly...</p>
    </div>
</body>
</html>
