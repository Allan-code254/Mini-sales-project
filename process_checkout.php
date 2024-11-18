<?php
session_start(); // Start session to access cart data

// Check if cart is not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Calculate the total sales and profit
    $total_sales = 0;
    $total_profit = 0;

    foreach ($_SESSION['cart'] as $item) {
        $total_sales += $item['price'] * $item['quantity'];
        // Assuming profit margin is 20%
        $total_profit += ($item['price'] * $item['quantity']) * 0.20;
    }

    // Get the current date, month, and year
    $sale_date = date('Y-m-d');
    $month = date('m');
    $year = date('Y');

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sales_system";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert transaction into the database
    $sql = "INSERT INTO transactions (sale_date, total_sales, total_profit, month, year) 
            VALUES ('$sale_date', '$total_sales', '$total_profit', '$month', '$year')";

    if ($conn->query($sql) === TRUE) {
        // Clear cart after successful transaction
        unset($_SESSION['cart']);
        // Redirect to transactions page (optional)
        header("Location: transactions.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "Your cart is empty!";
}
?>
