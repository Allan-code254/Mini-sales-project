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

// Fetch transactions
$sql = "SELECT sale_date, sales, profit FROM transactions ORDER BY sale_date DESC";
$result = $conn->query($sql);

// Prepare data for chart
$sales_data = [];
$profit_data = [];
$date_labels = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sales_data[] = $row['sales'];
        $profit_data[] = $row['profit'];
        $date_labels[] = date("d-m-Y H:i", strtotime($row['sale_date']));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - ReJo Sales</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Header styling */
        #header { display: flex; justify-content: space-between; align-items: center; background-color: #333; color: white; padding: 10px 20px; }
        #header a { color: white; text-decoration: none; }
        #navbar { display: flex; list-style: none; }
        #navbar li { margin: 0 10px; }
        #navbar a:hover { text-decoration: underline; }
        .logo { height: 40px; }
        table { width: 100%; margin: 20px 0; border-collapse: collapse; text-align: center; background-color: #fff; border: 1px solid #ddd; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background-color: #f4f4f4; }
        .chart-container { width: 70%; margin: 30px auto; }
        .container { max-width: 900px; margin: auto; padding: 20px; }
        .table-container { text-align: center; }
        h1, h2 { text-align: center; color: #333; }
    </style>
</head>
<body>
    <section id="header">
        <a href="index.php"><img src="img/logo.png" class="logo" alt="Logo"></a>
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
    </section>

    <div class="container">
        <h1>Transaction History</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Sale Date</th>
                        <th>Sales (Ksh)</th>
                        <th>Profit (Ksh)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php foreach ($sales_data as $index => $sales): ?>
                            <tr>
                                <td><?php echo $date_labels[$index]; ?></td>
                                <td><?php echo number_format($sales, 2); ?></td>
                                <td><?php echo number_format($profit_data[$index], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No transactions found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h2>Financial Overview</h2>
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Total Sales', 'Total Profit'],
                datasets: [{
                    label: 'Financial Overview',
                    data: [
                        <?php echo array_sum($sales_data); ?>, 
                        <?php echo array_sum($profit_data); ?>
                    ],
                    backgroundColor: ['#36A2EB', '#FF6384'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const value = tooltipItem.raw;
                                return `Ksh ${value.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
