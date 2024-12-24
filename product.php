<?php
// Get the product ID from the URL
$productId = $_GET['id'];

// Fetch product details
include('db.php');

// Query to fetch the product details
$sql = "SELECT * FROM products WHERE id = $productId";
$result = $connection->query($sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $connection->error); // Display a message if the query fails
}

$product = $result->fetch_assoc();

// Query to fetch other products (excluding the current product)
$relatedProductsSql = "SELECT * FROM products WHERE id != $productId LIMIT 4"; // Fetch 4 other products
$relatedProductsResult = $connection->query($relatedProductsSql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tsavo - Product Details</title>
    <link rel="stylesheet" href="2style.css">
    <link rel="stylesheet" href="sty7.css">
    <style>
        .off-screen-menu {
    background-color: #0d1117;
    color: rgb(248, 245, 245);
    height: 100vh;
    width: 250px;
    position: fixed;
    top: 0;
    z-index: 1000;
    right: -250px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    font-size: 1.5rem;
    transition: right 0.3s ease;
}

.off-screen-menu.active {
    right: 0;
}

.off-screen-menu ul {
    list-style: none;
    padding: 0;
}

.off-screen-menu li {
    margin: 20px 0;
    display: block;
    padding: 10px 15px;
    border-bottom: 1px solid #ccc;
}

.off-screen-menu a {
    color: white;
    text-decoration: none;
    font-size: 1.5rem;
}

.ham-menu {
    height: 50px;
    width: 50px;
    margin-left: auto;
    position: relative;
    cursor: pointer;
    z-index: 1000;
}

.ham-menu span {
    height: 5px;
    width: 100%;
    background-color: #fff;
    border-radius: 25px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.ham-menu span:nth-child(1) {
    top: 25%;
}

.ham-menu span:nth-child(2) {
    top: 50%;
}

.ham-menu span:nth-child(3) {
    top: 75%;
}

.ham-menu.active span:nth-child(1) {
    top: 50%;
    transform: translate(-50%, -50%) rotate(45deg);
}

.ham-menu.active span:nth-child(2) {
    opacity: 0;
}

.ham-menu.active span:nth-child(3) {
    top: 50%;
    transform: translate(-50%, 50%) rotate(-45deg);
}

        /* Related Products Row - Horizontal Layout */
.related-products {
    display: flex;
    justify-content: flex-start;
    overflow-x: auto; /* Allow horizontal scrolling if too many items */
    gap: 20px; /* Space between products */
    margin-top: 30px;
    padding: 20px 10px;
    scroll-snap-type: x mandatory; /* Ensure smooth scroll behavior */
}

/* Product Card for Related Products */
.product-card {
    flex: 0 0 calc(16.5% - 15px); /* 6 products per row (like the homepage) */
    max-width: calc(16.5% - 15px);
    background-color: #222; /* Dark background for the product card */
    border-radius: 8px;
    padding: 10px;
    text-align: center;
    scroll-snap-align: start; /* Ensure proper snap alignment */
    transition: transform 0.3s ease; /* Hover effect */
}

.product-card:hover {
    transform: translateY(-10px); /* Slight hover effect */
}

/* Product Image Styling */
.product-card img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    max-height: 250px; /* Limit the height of the image */
    object-fit: cover; /* Ensure proper image scaling */
}

/* Product Name Styling */
.product-card h3 {
    margin: 10px 0;
    font-size: 1rem;
    color: #4ea8de; /* Light blue for product title */
}

/* Price Styling */
.product-card .price {
    font-size: 0.9rem;
    color: #ff6600; /* Orange color for price emphasis */
}

/* View Details Button */
.product-card .view-details {
    display: inline-block;
    background-color: #4ea8de;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    margin-top: 10px;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.product-card .view-details:hover {
    background-color: #337c99; /* Darker blue when hovered */
}

/* Adjustments for Mobile */
@media (max-width: 768px) {
    .product-card {
        flex: 0 0 calc(30% - 15px); /* 3 products per row on tablets */
        max-width: calc(30% - 15px);
    }
}

@media (max-width: 480px) {
    .product-card {
        flex: 0 0 calc(50% - 15px); /* 2 products per row on mobile */
        max-width: calc(50% - 15px);
    }

    .product-card img {
        max-height: 200px; /* Adjust image height for smaller screens */
    }
}
/* Product Details Layout */
.product-details {
    display: flex;
    flex-wrap: wrap; /* Allow wrapping on smaller screens */
    justify-content: space-between; /* Space between image and details */
    gap: 20px;
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Image on the left */
.product-details .product-image {
    flex: 0 0 45%; /* Image takes 45% of the space on larger screens */
    max-width: 45%;
    border-radius: 8px;
    object-fit: cover; /* Ensures the image fills the space, cropping if necessary */
}

/* Product details on the right */
.product-details .product-info {
    flex: 0 0 50%; /* Details take 50% of the space */
    max-width: 50%;
    padding: 20px;
    color: #c9d1d9;
}

.product-details h1 {
    font-size: 2rem;
    color: #4ea8de;
    margin-bottom: 15px;
}

.product-details p {
    font-size: 1rem;
    line-height: 1.5;
    color: #c9d1d9;
}

.product-details .price {
    font-size: 1.2rem;
    font-weight: bold;
    color:rgb(4, 185, 170); /* Highlight the price in orange */
    margin-top: 10px;
}

.product-details .sizes, .product-details .colors, .product-details .stock {
    margin-top: 10px;
}

.product-details button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}

.product-details button:hover {
    background-color: #388E3C;
}

/* Adjustments for smaller screens */
@media (max-width: 768px) {
    .product-details {
        flex-direction: column; /* Stack the image and text vertically on smaller screens */
        align-items: center; /* Center the items */
        padding: 15px;
    }

    /* Product Details Layout */
.product-details .product-image {
    flex: 0 0 60%; /* Increase the image size to 60% of the available space */
    max-width: 60%; /* Ensure the image does not exceed 60% */
    border-radius: 8px;
    object-fit: contain; /* Ensure the image is not cropped */
    height: auto; /* Allow height to adjust based on the width */
}

/* Adjustments for smaller screens */
@media (max-width: 768px) {
    .product-details .product-image {
        width: 100%; /* Full width on smaller screens */
        height: auto; /* Maintain aspect ratio */
        object-fit: contain; /* Ensure the image doesn't get cropped */
    }
}

@media (max-width: 480px) {
    .product-details .product-image {
        width: 100%; /* Full width on very small screens */
        height: auto; /* Maintain aspect ratio */
    }
}


    .product-details .product-info {
        width: 100%; /* Adjust the text section width */
    }

    .product-details h1 {
        font-size: 1.5rem; /* Smaller heading on mobile */
    }

    .product-details p {
        font-size: 0.9rem; /* Smaller text on mobile */
    }

    .product-details .price {
        font-size: 1rem; /* Adjust price size on mobile */
    }

    .product-details button {
        width: 100%; /* Make the button full width on smaller screens */
    }
}

@media (max-width: 480px) {
    .product-details h1 {
        font-size: 1.2rem;
    }

    .product-details p {
        font-size: 0.85rem; /* Further reduce text size */
    }

    .product-details .price {
        font-size: 0.9rem;
    }
}



    </style>
    <script src="script.js"></script>
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

    <div class="product-details">
    <!-- Product Image -->
    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">

    <!-- Product Info Section -->
    <div class="product-info">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <p><strong>Price:</strong> Ksh <?php echo number_format($product['price_ksh'], 2); ?></p>
        <p><strong>Available Sizes:</strong> <?php echo htmlspecialchars($product['available_sizes']); ?></p>
        <p><strong>Available Colors:</strong> <?php echo htmlspecialchars($product['available_colors']); ?></p>
        <p><strong>Units Available:</strong> <?php echo htmlspecialchars($product['units_available']); ?></p>
        <form method="POST" action="cart.php?action=add&id=<?php echo $product['id']; ?>">
            <button type="submit">Add to Cart</button>
        </form>
    </div>
</div>


    <!-- Related Products Row -->
    <h2>Related Products</h2>
    <div class="related-products">
        <?php
        if ($relatedProductsResult->num_rows > 0) {
            while ($relatedProduct = $relatedProductsResult->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<img src="uploads/' . htmlspecialchars($relatedProduct['image']) . '" alt="' . htmlspecialchars($relatedProduct['name']) . '" class="product-image">';
                echo '<h3>' . htmlspecialchars($relatedProduct['name']) . '</h3>';
                echo '<p class="price">Ksh ' . number_format($relatedProduct['price_ksh'], 2) . '</p>';
                echo '<a href="product.php?id=' . $relatedProduct['id'] . '" class="view-details">View Details</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No related products found.</p>';
        }
        ?>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Tsavo Online Store | All Rights Reserved</p>
    </footer>

</body>
</html>

<?php
// Close the database connection
$connection->close();
?>
