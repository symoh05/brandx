<?php
session_start();

// Initialize the cart if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to the cart
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    // Check if the product already exists in the cart
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = 1; // Add to cart with quantity 1
    } else {
        $_SESSION['cart'][$productId]++; // Increment quantity if exists
    }
}

// Remove item from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    unset($_SESSION['cart'][$productId]); // Remove product from cart
}

// Get product details for display
include('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tsavo - Cart</title>
    <link rel="stylesheet" href="sty.css">
    <script src="script.js"></script>
    
    <style>
        /* Basic cart styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #0d1117; /* Replaced with dark background */
            color: #c9d1d9; /* Updated text color */
        }

        /* Header and Navigation */
        header {
            background-color: #161b22; /* Updated background color */
            color: #c9d1d9; /* Updated text color */
            padding: 1rem; /* Padding adjusted */
        }

        header nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        header nav ul li {
            display: inline;
            margin-right: 20px;
        }

        header nav ul li a {
            color: #c9d1d9; /* Updated link color */
            text-decoration: none;
            font-size: 18px;
        }

        /* Main Title and Description */
        h1 {
            text-align: center;
            font-size: 36px;
            color: #4ea8de; /* Updated main heading color */
        }

        h2 {
            text-align: center;
            font-size: 24px;
            color: #ff6600; /* Orange color for subheading */
        }

        .cart-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .cart-item {
            background-color: #161b22; /* Dark background for product */
            border: 1px solid #30363d; /* Subtle border */
            border-radius: 6px;
            overflow: hidden;
            padding: 10px;
            width: 100%;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease; /* Hover effect */
        }

        .cart-item img {
            max-width: 200px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .cart-item p {
            margin: 5px 0;
            text-align: center;
        }

        .cart-item a {
            color: #ff0000;
            text-decoration: none;
            margin-top: 10px;
        }

        .cart-item a:hover {
            text-decoration: underline;
        }

        .total-price {
            text-align: center;
            font-size: 1.5em;
            margin-top: 30px;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            padding: 15px;
            text-align: center;
            font-size: 1.2em;
            cursor: pointer;
            margin-top: 20px;
            background-color: none;
            color: rgb(4, 250, 238);
            text-decoration: none;
            border-radius: 5px;
            background-color: green;
            display: block;
            text-align: center;
            align-items: center;
        }

        .checkout-btn:hover {
            border: 1px solid rgb(33, 250, 4);
            background-color: none;
            background: none;
            transform: scale(1.05); /* Slight zoom effect */
        }
    </style>
</head>
<body>

<header>
    <div class="ham-menu" onclick="toggleMenu()">
        <span></span><span></span><span></span>
    </div>
    <h1>Sneaker Haven</h1>
    <div class="off-screen-menu" id="menu">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="checkout.php">Checkout</a></li>
            <li><a href="login.php">Admin</a></li>
        </ul>
    </div>
</header>

<h1>Your Shopping Cart</h1>

<div class="cart-items">
    <?php
    $totalPrice = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            // Query to get the product details
            $sql = "SELECT * FROM products WHERE id = $productId";
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $totalPrice += $row['price_ksh'] * $quantity;

                echo '<div class="cart-item">';
                echo '<img src="uploads/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<p class="quantity">Quantity: ' . $quantity . '</p>';
                echo '<p class="price">Price: Ksh ' . number_format($row['price_ksh'], 2) . '</p>';
                echo '<a href="cart.php?action=remove&id=' . $productId . '" class="remove-link">Remove</a>';
                echo '</div>';
            }
        }
        echo '<div class="total-price"><strong>Total Price: Ksh ' . number_format($totalPrice, 2) . '</strong></div>';
    } else {
        echo '<p>Your cart is empty.</p>';
    }
    ?>
</div>

<?php if (!empty($_SESSION['cart'])): ?>
    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
<?php endif; ?>

<footer>
    <p>© 2024 Tsavo Online Store</p>
</footer>

</body>
</html>

<?php
// Close the database connection
$connection->close();
?>
