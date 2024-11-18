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
                <li><a href="index.html">Home</a></li>
                <li><a class="active" href="shop.php">Shop</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="cart.html">Cart</a></li>
                <li><a href="transactions.html">Transactions</a></li>
                <li><a href="signup.html" class="auth-link">Signup</a></li>
                <li><a href="login.html" class="auth-link">Login</a></li>
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
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product'>
                            <img src='" . $row['img/products'] . "' alt='" . $row['name'] . "'>
                            <h3>" . $row['name'] . "</h3>
                            <p>" . $row['description'] . "</p>
                            <p>$" . $row['price'] . "</p>
                            <button>Add to Cart</button>
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
