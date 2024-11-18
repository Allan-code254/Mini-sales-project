<?php
// Fetch transactions from the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sales_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM transactions ORDER BY sale_date DESC";
$result = $conn->query($sql);

$total_sales = 0;
$total_profit = 0;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $total_sales += $row['total_sales'];
        $total_profit += $row['total_profit'];
    }
} else {
    echo "No transactions found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - ReJo Sales</title>
    <link rel="stylesheet" href="style.css">
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <li><a class="active" href="transactions.php">Transactions</a></li>
                <li><a href="signup.php" class="auth-link">Signup</a></li>
                <li><a href="login.php" class="auth-link">Login</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </section>

    <main>
        <section class="transactions-section">
            <h2>Transaction History</h2>
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Sales</th>
                        <th>Total Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['sale_date']}</td>
                                    <td>" . number_format($row['total_sales'], 2) . "</td>
                                    <td>" . number_format($row['total_profit'], 2) . "</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No transactions found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Display Pie Chart -->
            <div class="chart-container" style="width: 50%; margin: 0 auto; padding-top: 2rem;">
                <canvas id="transactionsPieChart"></canvas>
            </div>

        </section>
    </main>

    <script>
        // Pie Chart data
        const data = {
            labels: ['Total Sales', 'Total Profit'],
            datasets: [{
                data: [<?php echo $total_sales; ?>, <?php echo $total_profit; ?>],
                backgroundColor: ['#36a2eb', '#ff6384'],
                hoverOffset: 4
            }]
        };

        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': Ksh.' + tooltipItem.raw.toFixed(2);
                            }
                        }
                    }
                }
            }
        };

        // Create the pie chart
        var ctx = document.getElementById('transactionsPieChart').getContext('2d');
        new Chart(ctx, config);
    </script>
</body>
</html>
