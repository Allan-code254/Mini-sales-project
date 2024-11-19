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

// Fetch the most recent transaction data
$sql = "SELECT t.sale_date, t.sales, t.buying_price, t.sales - t.buying_price AS profit FROM transactions t ORDER BY t.sale_date DESC LIMIT 1";
$result = $conn->query($sql);

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
        /* Styles for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        #header {
            background-color: #333;
            padding: 10px 0;
            color: white;
            text-align: center;
        }
        .logo {
            width: 100px;
        }
        #navbar {
            list-style: none;
            padding: 0;
        }
        #navbar li {
            display: inline;
            margin: 0 15px;
        }
        #navbar a {
            color: white;
            text-decoration: none;
        }
        .container {
            padding: 20px;
            max-width: 900px;
            margin: auto;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            text-decoration: none;
            border-radius: 5px;
        }
        .chart-container {
            width: 50%;
            margin: 50px auto;
        }
    </style>
</head>
<body>
    <section id="header">
        <a href="#"><img src="img/logo.png" class="logo" alt="Logo"></a>
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

    <div class="container">
        <h1>Transaction History</h1>

        <table>
            <thead>
                <tr>
                    <th>Sale Date</th>
                    <th>Sales (Ksh)</th>
                    <th>Profit (Ksh)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ensure there are results
                if ($result->num_rows > 0) {
                    // Output the most recent transaction row
                    $row = $result->fetch_assoc();
                    // Format the sale_date as DD-MM-YYYY
                    $formatted_date = date("d-m-Y", strtotime($row['sale_date']));
                    $sales = $row['sales'];
                    $profit = $row['profit'];
                    echo "<tr>
                            <td>" . $formatted_date . "</td>
                            <td>" . number_format($sales, 2) . "</td>
                            <td>" . number_format($profit, 2) . "</td>
                        </tr>";
                } else {
                    echo "<tr><td colspan='3'>No recent transactions available</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>

        <script>
            // Fetch the data for the chart
            <?php
            $sales_data = [];
            $profit_data = [];
            $labels = [];
            $sql = "SELECT sale_date, sales, buying_price, sales - buying_price AS profit FROM transactions ORDER BY sale_date DESC LIMIT 1"; // Only fetching the latest transaction
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $sales_data[] = $row['sales'];
                $profit_data[] = $row['profit'];
                $labels[] = date("d-m-Y", strtotime($row['sale_date']));
            }
            ?>

            const salesData = <?php echo json_encode($sales_data); ?>;
            const profitData = <?php echo json_encode($profit_data); ?>;
            const labels = <?php echo json_encode($labels); ?>;

            // Create the pie chart using Chart.js
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales vs Profit',
                        data: salesData,
                        backgroundColor: ['#36a2eb', '#ff6384'],
                        borderColor: ['#36a2eb', '#ff6384'],
                        borderWidth: 1
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
                                    const label = tooltipItem.label || '';
                                    const value = tooltipItem.raw;
                                    return label + ': Ksh ' + value.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        </script>

    </div>
</body>
</html>

<?php
$conn->close();
?>
