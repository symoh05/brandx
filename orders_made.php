<?php
session_start();
include('db.php');  // Database connection

// Check if a removal request was made
if (isset($_GET['remove'])) {
    $orderIdToRemove = intval($_GET['remove']);  // Get the order ID to remove

    // Delete related order details first
    $deleteOrderDetailsQuery = "DELETE FROM order_details WHERE order_id = $orderIdToRemove";
    $connection->query($deleteOrderDetailsQuery);

    // Now delete the order from the orders_made table
    $deleteOrderQuery = "DELETE FROM orders_made WHERE id = $orderIdToRemove";
    if ($connection->query($deleteOrderQuery)) {
        // Successfully deleted the order, redirect to avoid resubmission on refresh
        header("Location: orders_made.php");
        exit;
    } else {
        echo "Error deleting order: " . $connection->error;
    }
}

// Query to fetch all orders with their product names and other details
$query = "SELECT o.id AS order_id, o.name AS customer_name, o.email, o.phone, o.location, o.total_price, o.order_date, 
                 GROUP_CONCAT(p.name ORDER BY p.name ASC) AS product_names
          FROM orders_made o
          JOIN order_details od ON o.id = od.order_id
          JOIN products p ON od.product_id = p.id
          GROUP BY o.id
          ORDER BY o.order_date DESC";
$result = $connection->query($query);

// Check if the query was successful
if (!$result) {
    die("Error in SQL query: " . $connection->error);
}

// Fetch all orders into an array
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    $orders = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Made</title>
    <link rel="stylesheet" href="sty.css">
    <script src="script.js"></script>
   
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #0d1117;
            color: #c9d1d9;
            font-family: Arial, sans-serif;
        
        }
        .orders-container {
            max-width: 100%;
            margin: 0 auto;
           
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #238636;
            border-radius: 6px;
            background-color: #0d1117;
            color: #c9d1d9;
            
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        td {
            border: 1px solid #238636;
            border-radius: 6px;
            background-color: #0d1117;
            color: #c9d1d9;
        }
        .action-btn {
            padding: 8px 16px;
            background-color: none;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid rgb(179, 0, 0);
        }
        .action-btn:hover {
            background-color:rgb(179, 0, 0);
            color: black;
        }
    </style>
</head>
<div class="ham-menu" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </div>
        <h1>Sneaker Haven</h1>
        <div class="off-screen-menu" id="menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="add_product.php">Add Products</a></li>
            </ul>
        </div>
    </header>
<body>

<header>
    <h1>Orders Made</h1>
</header>

<div class="orders-container">
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Delivery Location</th>
                <th>Total Price (KSH)</th>
                <th>Order Date</th>
                <th>Product Name(s)</th>
                <th>Action</th> <!-- Added Action column for Remove -->
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['email']); ?></td>
                        <td><?php echo htmlspecialchars($order['phone']); ?></td>
                        <td><?php echo isset($order['location']) ? htmlspecialchars($order['location']) : 'N/A'; ?></td>
                        <td><?php echo number_format($order['total_price'], 2); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($order['order_date'])); ?></td>
                        <td><?php echo htmlspecialchars($order['product_names']); ?></td>
                        <td><a href="?remove=<?php echo $order['order_id']; ?>" class="action-btn">Remove</a></td> <!-- Remove button -->
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No orders have been placed yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
