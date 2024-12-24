<?php
// Check if a category is set in the URL
if (isset($_GET['category'])) {
    $category = $_GET['category'];
} else {
    // If no category is selected, default to 'home' or some other fallback
    $category = 'home';
}

// Fetch products based on category
include('db.php');

// Ensure the category is valid by sanitizing it
$category = mysqli_real_escape_string($connection, $category);

$sql = "SELECT * FROM products WHERE category = '$category'";
$result = $connection->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tsavo - <?php echo ucfirst($category); ?> Products</title>
    <link rel="stylesheet" href="sty.css">
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
    <a class="btn" href="index.php">Back</a>


    <h1><?php echo ucfirst($category); ?> Products</h1>
    <div class="product-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagePath = 'uploads/' . htmlspecialchars($row['image']);
                echo '<div class="product">';
                echo '<a href="product.php?id=' . $row['id'] . '"><img src="' . $imagePath . '" alt="' . htmlspecialchars($row['name']) . '" class="product-image"></a>';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                // Now only display the price in Ksh
                echo '<p><strong>Price: Ksh ' . number_format($row['price_ksh'], 2) . '</strong></p>';
                echo '</div>';
            }
        } else {
            echo '<p>No products in this category.</p>';
        }
        ?>
    </div>
</body>
</html>

<?php
// Close the database connection
$connection->close();
?>
