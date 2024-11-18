<?php
session_start(); // Start the session to store cart data

// Initialize total sales and profit
$total_sales = 0;
$total_profit = 0;

// Fetch cart items from the session (or database if stored permanently)
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $total_sales += $item['price'] * $item['quantity'];
        // Assuming a profit margin of 20% (adjust as needed)
        $total_profit += ($item['price'] * $item['quantity']) * 0.20;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - ReJo Sales</title>
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

    <main>
        <section class="cart-section">
            <h2>Your Cart</h2>
            <p>Here are the items you've selected for purchase:</p>
            <div class="cart-container">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Display cart items
                        if (!empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $product_id => $item) {
                                echo "<tr>
                                        <td>{$item['name']}</td>
                                        <td>{$item['price']}</td>
                                        <td>{$item['quantity']}</td>
                                        <td>" . $item['price'] * $item['quantity'] . "</td>
                                      </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <div class="cart-summary">
                    <h3>Cart Summary</h3>
                    <p><strong>Total Sales:</strong> Ksh.<span id="cart-total"><?= number_format($total_sales, 2) ?></span></p>
                    <p><strong>Total Profit:</strong> Ksh.<span id="cart-profit"><?= number_format($total_profit, 2) ?></span></p>
                    <form action="process_checkout.php" method="POST">
                        <button type="submit" id="checkout-btn">Proceed to Checkout</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
