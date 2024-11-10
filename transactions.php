<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Process the form data if submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = mysqli_real_escape_string($conn, $_POST['product']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);

    $query = "INSERT INTO transactions (product, amount, date, type) VALUES ('$product', '$amount', '$date', '$type')";
    mysqli_query($conn, $query);
}

// Fetch all transactions from the database
$transactions = mysqli_query($conn, "SELECT * FROM transactions ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - ReJo Sales</title>
    <style>
        /* Reset and basic styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
        }

        /* Container for transactions */
        .transaction-container {
            width: 80%;
            max-width: 900px;
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 1rem;
            text-align: center;
        }

        /* Form styles */
        .transaction-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .transaction-form input, .transaction-form select, .transaction-form button {
            flex: 1;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .transaction-form button {
            background-color: #3b82f6;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .transaction-form button:hover {
            background-color: #2563eb;
        }

        /* Table styles */
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .transaction-table th, .transaction-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .transaction-table th {
            background-color: #3b82f6;
            color: #fff;
            font-weight: bold;
        }

        .transaction-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        /* Button to delete */
        .delete-btn {
            padding: 0.5rem 1rem;
            color: #fff;
            background-color: #e53e3e;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #c53030;
        }
    </style>
</head>
<body>
    <div class="transaction-container">
        <h2>Transactions</h2>
        
        <!-- Form to add a transaction -->
        <form action="transactions.php" method="POST" class="transaction-form">
            <input type="text" name="product" placeholder="Product Name" required>
            <input type="number" name="amount" placeholder="Amount" required>
            <input type="date" name="date" required>
            <select name="type" required>
                <option value="" disabled selected>Type</option>
                <option value="sale">Sale</option>
                <option value="purchase">Purchase</option>
            </select>
            <button type="submit">Add Transaction</button>
        </form>

        <!-- Table to display transactions -->
        <table class="transaction-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($transactions)) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['product']); ?></td>
                        <td><?php echo htmlspecialchars($row['amount']); ?></td>
                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                        <td>
                            <form action="delete_transaction.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
