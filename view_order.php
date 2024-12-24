<?php
session_start();
include('db.php'); // Database connection



// Get the order ID from the URL
$orderId = isset($_GET['order_id']) ? $_GET['order_id'] : 0;
if ($orderId == 0) {
    die("Invalid order ID.");
}

// Fetch order information
$orderQuery = "SELECT * FROM orders_made WHERE id = $orderId";
$orderResult = $connection->query($orderQuery);
$order = $orderResult->fetch_assoc();

// Fetch products in the order
$orderDetailsQuery = "SELECT od.*, p.name AS product_name, p.price_ksh
                       FROM order_details od
                       JOIN products p ON od.product_id = p.id
                       WHERE od.order_id = $orderId";
$orderDetailsResult = $connection->query($orderDetailsQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .order-details-container {
            width: 80%;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .order-details-container h2 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<header>
    <h1>Order Details</h1>
</header>

<div class="order-details-container">
    <h2>Order ID: <?php echo $order['id']; ?></h2>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($order['location']); ?></p>
    <p><strong>Total Price:</strong> KSH <?php echo number_format($order['total_price'], 2); ?></p>
    <p><strong>Order Date:</strong> <?php echo date('Y-m-d H:i', strtotime($order['order_date'])); ?></p>

    <h3>Products in Order</h3>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price (KSH)</th>
                <th>Total Price (KSH)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $orderDetailsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price_ksh'], 2); ?></td>
                    <td><?php echo number_format($item['price_ksh'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
