<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - ReJo Sales</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section id="header">
        <a href="#"><img src="img/logo.png" class="logo" alt=""></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a class="active" href="shop.php">Shop</a></li>
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
        <h1>Shop Products</h1>

        <div class="products-grid">
            <?php
            // Fetch products from the database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "sales_system";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Display each product
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product'>
                            <img src='" . $row['image_path'] . "' alt='" . $row['name'] . "'>
                            <h3>" . $row['name'] . "</h3>
                            <p>" . $row['description'] . "</p>
                            <p>Ksh " . $row['price'] . "</p>
                            <form method='POST' action='cart.php'>
                                <input type='hidden' name='product_id' value='" . $row['id'] . "'>
                                <input type='hidden' name='product_name' value='" . $row['name'] . "'>
                                <input type='hidden' name='product_price' value='" . $row['price'] . "'>
                                <input type='hidden' name='product_image' value='" . $row['image_path'] . "'>
                                <button type='submit'>Add to Cart</button>
                            </form>
                          </div>";
                }
            } else {
                echo "No products available.";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
