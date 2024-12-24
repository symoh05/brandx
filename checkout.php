<?php
session_start();
include('db.php');  // Database connection

// Ensure the cart is not empty before proceeding to checkout
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");  // Redirect to cart if no items in the cart
    exit;
}

// Handle the order submission after form is posted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    // Get user details from the checkout form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    // Calculate the total price and store product details for the order
    $totalPrice = 0;
    $orderProducts = [];
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $sql = "SELECT price_ksh, name FROM products WHERE id = $productId";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $totalPrice += $product['price_ksh'] * $quantity;
            $orderProducts[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product['price_ksh'],
                'name' => $product['name']
            ];
        }
    }

    // Insert the order into the orders_made table
    $orderQuery = "INSERT INTO orders_made (name, email, phone, location, total_price, order_date) 
                   VALUES ('$name', '$email', '$phone', '$location', '$totalPrice', NOW())";
    
    if ($connection->query($orderQuery)) {
        $orderId = $connection->insert_id;

        // Insert products into the order_details table
        foreach ($orderProducts as $product) {
            $orderDetailsQuery = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                                  VALUES ('$orderId', '{$product['product_id']}', '{$product['quantity']}', '{$product['price']}')";
            $connection->query($orderDetailsQuery);
        }

        // Clear the cart after the order is placed
        unset($_SESSION['cart']);

        // Set a success message and redirect to index.php
        $_SESSION['order_success'] = "Your order has been placed successfully!";
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['order_error'] = "There was an error placing your order. Please try again.";
    }
}

// Get product details for display (will be hidden)
$cartProducts = [];
$totalPrice = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $sql = "SELECT * FROM products WHERE id = $productId";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $totalPrice += $product['price_ksh'] * $quantity;
            $cartProducts[] = ['product' => $product, 'quantity' => $quantity];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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

        header {
            position: relative;
            padding: 1rem;
            background-color: #161b22;
        
        }
        .checkout-container {
            max-width: 600px;
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
        .checkout-form label {
            display: block;
            margin: 5px 0;
        }
        .checkout-form input, .checkout-form textarea {
            padding: 10px;
            border-radius: 6px;
            background-color: #0d1117;
            border: 1px solid #238636;
            color: #c9d1d9;
            width: 100%;
    
        }
        .checkout-form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }
        .checkout-form button:hover {
            background: none;
            border: 1px solid rgb(33, 250, 4);
        }
        .success-message,
        .error-message {
            color: green;
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .error-message {
            color: red;
        }

        /* Hidden product details */
        .product-details {
            display: none;
        }
    </style>
</head>
<body>

<header>
    <h1>Checkout</h1>
</header>

<div class="checkout-container">
    <?php if (isset($_SESSION['order_success'])): ?>
        <div class="success-message">
            <?php echo $_SESSION['order_success']; unset($_SESSION['order_success']); ?>
        </div>
    <?php elseif (isset($_SESSION['order_error'])): ?>
        <div class="error-message">
            <?php echo $_SESSION['order_error']; unset($_SESSION['order_error']); ?>
        </div>
    <?php endif; ?>

    <form action="checkout.php" method="POST" class="checkout-form">
        <h2>Enter your details</h2>

        <!-- User's details inputs -->
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" required>

        <label for="location">Shipping Address</label>
        <textarea id="location" name="location" rows="4" required></textarea>

       
        <button type="submit" name="checkout">Place Order</button>
    </form>
</div>

</body>
</html>
