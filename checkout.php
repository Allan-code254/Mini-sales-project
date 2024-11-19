<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sales_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize total cart value
$cart_total = 0;
$total_profit = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_total += $item['price'] * $item['quantity'];

        // Fetch the buying price for each product in the cart
        $stmt = $conn->prepare("SELECT buying_price FROM products WHERE id = ?");
        $stmt->bind_param("i", $item['id']);
        $stmt->execute();
        $stmt->bind_result($buying_price);
        $stmt->fetch();
        $stmt->close();

        // Calculate profit (selling price - buying price)
        $total_profit += ($item['price'] - $buying_price) * $item['quantity'];
    }
}

// Simulate Payment and Add to Transactions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_number = $_POST['phone_number'];

    // Validate phone number
    if (preg_match('/^07[0-9]{8}$/', $phone_number)) {
        // Get the current date for the sale
        $sale_date = date('Y-m-d');

        // Insert transaction details into the transactions table
        $stmt = $conn->prepare("INSERT INTO transactions (sale_date, sales, buying_price, profit) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sddd", $sale_date, $cart_total, $cart_total - $total_profit, $total_profit);

        if ($stmt->execute()) {
            // Clear cart after successful "payment"
            unset($_SESSION['cart']);
            $_SESSION['payment_status'] = 'success';
            $_SESSION['payment_total'] = $cart_total;

            // Redirect to confirmation page
            header('Location: confirmation.php');
            exit();
        } else {
            $error_message = "Failed to record the transaction.";
        }
    } else {
        $error_message = 'Invalid phone number format. Use 07XXXXXXXX.';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ReJo Sales</title>
    <link rel="stylesheet" href="style.css">
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
                <li><a class="active" href="cart.php">Cart</a></li>
                <li><a href="transactions.php">Transactions</a></li>
                <li><a href="signup.php" class="auth-link">Signup</a></li>
                <li><a href="login.php" class="auth-link">Login</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </section>

    <div class="container">
        <h1>Proceed to Checkout</h1>

        <div class="checkout-form">
            <p><strong>Total Amount:</strong> Ksh <?php echo number_format($cart_total, 2); ?></p>
            <p><strong>Total Profit:</strong> Ksh <?php echo number_format($total_profit, 2); ?></p>

            <?php if (isset($error_message)): ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <label for="phone_number">Enter Phone Number (M-Pesa Simulation):</label>
                <input type="text" id="phone_number" name="phone_number" placeholder="07XXXXXXXX" required>
                <button type="submit">Simulate Payment</button>
            </form>
        </div>
    </div>
</body>
</html>
