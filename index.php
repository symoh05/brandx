<?php
// Include the database connection
include('db.php');

// Start the session to use cart functionality
session_start();

// Query to fetch all products from the database
$sql = "SELECT * FROM products";
$result = $connection->query($sql);  // Execute the query
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tsavo - Clothing, Shoes, Watches and More</title>
    <link rel="stylesheet" href="sty.css">  <!-- Link to your CSS file -->
    <script src="script.js"></script>
    <style>
       /* General page styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #0d1117; /* Replaced with dark background */
    color: #c9d1d9; /* Updated text color */
}

/* Main Title and Description */
h1 {
    text-align: center;
    font-size: 36px;
    color: #4ea8de; /* Updated main heading color */
    font-family: 'Times New Roman', Times, serif;
}

h3{
    text-align: left;
    font-size: 36px;
    color: darkgreen; /* Updated main heading color */
    font-family: cursive;
}

h2 {
    text-align: center;
    font-size: 24px;
    color: rgb(255, 255, 255); /* Orange color for subheading */
    font-weight: lighter;
    font-style: italic;
    font-family: cursive;
}

/* Categories Section */
.categories {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.categories a {
    background-color:rgb(1, 72, 116); /* Orange background */
    color: white;
    padding: 10px 20px;
    margin: 0 10px;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}

.categories a:hover {
    border: 1px solid rgb(5, 199, 38);
    background: none;
}

/* Scrollable Row */
.product-container {
    display: flex;
    overflow-x: auto;
    gap: 15px;
    padding: 10px 20px;
    scroll-behavior: smooth;
    scroll-snap-type: x mandatory; /* Ensures smooth snap scroll */
}

/* Scrollbar Styling */
.product-container::-webkit-scrollbar {
    height: 8px;
}

.product-container::-webkit-scrollbar-thumb {
    background: #444;
    border-radius: 10px;
}

/* Product Card */
.product {
    flex: 0 0 calc(16.5% - 15px); /* For large screens, 6 products per row */
   /* max-width: calc(16.5% - 15px); /* Ensure fixed width for each product */
    background: #222; /* Dark background for the card */
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    scroll-snap-align: start; /* Snap each product into place */
    transition: transform 0.3s ease; /* Hover effect */

}

.product:hover {
    transform: translateY(-10px); /* Elevate on hover */
}

/* Product Image */
.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
    max-height: 150px; /* Limit image height */
}

/* Product Name */
.product h3 {
    margin: 10px 0;
    font-size: 1rem;
    color: #4ea8de; /* Blue for product title */
}

/* Product Price */
.product p {
    font-size: 0.9rem;
    color: #8b949e; /* Lighter color for the price */
}

.product p strong {
    color: #ff6600; /* Orange for price emphasis */
}

/* Add to Cart Button */
.product .add-to-cart {
    margin-top: 10px;
    background: #4CAF50; /* Green for the button */
    color: #fff;
    border: none;
    padding: 6px 12px;
    font-size: 0.9rem;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.product .add-to-cart:hover {
    background: #388E3C; /* Hover effect for the button */
}

/* Scrollable Container - Mobile Specific */
@media (max-width: 768px) {
    .product-container {
        padding: 10px;

    }

    .product {
        flex: 0 0 calc(30% - 15px); /* 3 products per row on smaller tablets */
        max-width: calc(30% - 15px);
    }

    .product-container::-webkit-scrollbar {
        height: 6px;
    }
}

@media (max-width: 480px) {
    .product-container {
        padding: 10px;
    }

    .product {
        flex: 0 0 calc(40% - 15px); /* 2.5 products visible per row on mobile */
        max-width: calc(40% - 15px);
    }

    .product-image {
        max-height: 200px; /* Adjust image height for mobile screens */
    }
}

</style>
</head>
<body>
    
    <header>
        <div class="ham-menu" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </div>
        <h1>Sneak&Chic</h1>
        <div class="off-screen-menu" id="menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="checkout.php">Checkout</a></li>
                <li><a href="login.php">Admin Dashboard</a></li>
            </ul>
        </div>
    </header>

    <!-- Product Section -->
    <div class="product-container">
        <?php
        // Check if there are any products in the database
        if ($result->num_rows > 0) {
            // Loop through each product and display it
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product">';
                
                // Handle image path dynamically and safely
                $imagePath = 'uploads/' . htmlspecialchars($row['image']);
                
                // Display the product image (image filename stored in DB)
                echo '<a href="product.php?id=' . $row['id'] . '"><img src="' . $imagePath . '" alt="' . htmlspecialchars($row['name']) . '" class="product-image"></a>';
                
                // Display product name and price (only in KSH)
                $priceKsh = number_format($row['price_ksh'], 2);  // Use price_ksh directly from the database
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p><strong>Price:</strong> Ksh ' . $priceKsh . '</p>';
                
                // Add to Cart Button with a link to the cart page
                echo '<a href="cart.php?action=add&id=' . $row['id'] . '" class="add-to-cart">Add to Cart</a>';
                
                echo '</div>';
            }
        } else {
            // Message when there are no products
            echo '<p>No products available.</p>';
        }
        ?>
    </div>

</body>
</html>

<?php
// Close the database connection
$connection->close();
?>
